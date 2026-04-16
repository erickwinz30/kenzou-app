<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
  protected $midtrans;

  public function __construct(MidtransService $midtrans)
  {
    $this->midtrans = $midtrans;
  }

  private function mapPaidOffStatus(string $transactionStatus, ?string $fraudStatus = null): bool
  {
    if ($transactionStatus === 'capture') {
      return $fraudStatus === 'accept';
    }

    return in_array($transactionStatus, ['settlement'], true);
  }

  public function confirmPayment(Request $request)
  {
    try {
      $validated = $request->validate([
        'order_id' => 'required|string|exists:transaksis,id',
      ]);

      $orderId = (string) $validated['order_id'];
      $status = $this->midtrans->getTransactionStatus($orderId);
      $transactionStatus = (string) ($status['transaction_status'] ?? '');

      if ($transactionStatus === '') {
        return response()->json([
          'message' => 'Status transaksi Midtrans tidak ditemukan.',
        ], 422);
      }

      $transaction = Transaksi::findOrFail($orderId);
      $transaction->is_paid_off = $this->mapPaidOffStatus($transactionStatus, $status['fraud_status'] ?? null);

      if (!empty($status['payment_type'])) {
        $transaction->metode_pembayaran = (string) $status['payment_type'];
      }

      $transaction->save();

      return response()->json([
        'message' => 'Status pembayaran berhasil disinkronkan.',
        'order_id' => $orderId,
        'transaction_status' => $transactionStatus,
        'is_paid_off' => (bool) $transaction->is_paid_off,
      ]);
    } catch (\Throwable $e) {
      Log::error('Payment confirmPayment error', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'message' => 'Gagal sinkronisasi status pembayaran.',
      ], 500);
    }
  }

  public function createTransaction(Request $request)
  {
    try {
      $routeTransactionId = $request->route('transaction');
      if (!empty($routeTransactionId)) {
        $request->merge(['transaction_id' => (string) $routeTransactionId]);
      }

      $validated = $request->validate([
        'transaction_id' => 'nullable|string|exists:transaksis,id',
        'layanan_id' => 'nullable|array',
        'layanan_id.*' => 'exists:layanans,id',
        'nomor_telepon' => 'nullable|string|min:10|max:15',
        'subtotal' => 'nullable|numeric|min:1',
        'metode_pembayaran' => 'nullable|in:qris,tunai',
      ]);

      $paymentMethod = $validated['metode_pembayaran'] ?? 'qris';
      if ($paymentMethod !== 'qris') {
        return response()->json([
          'message' => 'Midtrans hanya dipakai untuk metode pembayaran qris.',
        ], 422);
      }

      $orderId = null;
      $grossAmount = 0;
      $itemDetails = [];
      $firstName = 'Pelanggan';
      $email = null;
      $phone = $validated['nomor_telepon'] ?? null;

      if (!empty($validated['transaction_id'])) {
        $transaction = Transaksi::with(['detail_layanan.layanan', 'pelanggan.member'])->findOrFail($validated['transaction_id']);

        $orderId = (string) $transaction->id;
        $grossAmount = (int) round((float) $transaction->subtotal);
        $phone = $transaction->pelanggan?->nomor_telepon;

        if ($transaction->pelanggan?->member) {
          $firstName = $transaction->pelanggan->member->nama ?? $firstName;
          $email = $transaction->pelanggan->member->email;
        }

        foreach ($transaction->detail_layanan as $detail) {
          if (!$detail->layanan) {
            continue;
          }

          $itemDetails[] = [
            'id' => (string) $detail->layanan->id,
            'price' => (int) round((float) $detail->layanan->harga),
            'quantity' => 1,
            'name' => Str::limit($detail->layanan->nama_layanan, 50, ''),
          ];
        }
      } else {
        if (empty($validated['layanan_id']) || empty($validated['subtotal']) || empty($validated['nomor_telepon'])) {
          return response()->json([
            'message' => 'layanan_id, subtotal, dan nomor_telepon wajib diisi saat transaction_id tidak dikirim.',
          ], 422);
        }

        $selectedLayanan = Layanan::whereIn('id', $validated['layanan_id'])->get();
        if ($selectedLayanan->isEmpty()) {
          return response()->json(['message' => 'Data layanan tidak ditemukan.'], 422);
        }

        $orderId = 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
        $grossAmount = (int) round((float) $validated['subtotal']);

        foreach ($selectedLayanan as $layanan) {
          $itemDetails[] = [
            'id' => (string) $layanan->id,
            'price' => (int) round((float) $layanan->harga),
            'quantity' => 1,
            'name' => Str::limit($layanan->nama_layanan, 50, ''),
          ];
        }

        $pelanggan = Pelanggan::with('member')->where('nomor_telepon', $validated['nomor_telepon'])->first();
        if ($pelanggan?->member) {
          $firstName = $pelanggan->member->nama ?? $firstName;
          $email = $pelanggan->member->email;
        }
      }

      if ($grossAmount <= 0 || empty($itemDetails)) {
        return response()->json([
          'message' => 'Total pembayaran atau detail item tidak valid.',
        ], 422);
      }

      $itemsTotal = collect($itemDetails)->sum(function ($item) {
        return ($item['price'] * $item['quantity']);
      });

      if ($itemsTotal !== $grossAmount) {
        $adjustment = $grossAmount - $itemsTotal;
        $itemDetails[] = [
          'id' => 'ADJUSTMENT',
          'price' => $adjustment,
          'quantity' => 1,
          'name' => $adjustment < 0 ? 'Diskon Transaksi' : 'Penyesuaian Transaksi',
        ];
      }

      $params = [
        'transaction_details' => [
          'order_id' => $orderId,
          'gross_amount' => $grossAmount,
        ],
        'item_details' => $itemDetails,
        'customer_details' => array_filter([
          'first_name' => $firstName,
          'email' => $email,
          'phone' => $phone,
        ]),
      ];

      $enabledPayments = config('midtrans.snap_enabled_payments', []);
      if (is_string($enabledPayments)) {
        $enabledPayments = array_filter(array_map('trim', explode(',', $enabledPayments)));
      }

      // Only send enabled_payments when explicitly configured.
      // If empty, Midtrans will show payment channels available in merchant account.
      if (is_array($enabledPayments) && !empty($enabledPayments)) {
        $params['enabled_payments'] = array_values($enabledPayments);
      }

      $midtransTransaction = $this->midtrans->createTransaction($params);

      if ($request->expectsJson() || $request->wantsJson()) {
        return response()->json([
          'message' => 'Transaksi Midtrans berhasil dibuat.',
          'order_id' => $orderId,
          'snap_token' => $midtransTransaction['token'],
          'redirect_url' => $midtransTransaction['redirect_url'],
        ]);
      }

      if (!empty($validated['transaction_id'])) {
        return redirect()->route('midtrans.qris', [
          'transaction' => $validated['transaction_id'],
          'snap_token' => $midtransTransaction['token'],
        ]);
      }

      return response()->json([
        'message' => 'Transaksi Midtrans berhasil dibuat.',
        'order_id' => $orderId,
        'snap_token' => $midtransTransaction['token'],
        'redirect_url' => $midtransTransaction['redirect_url'],
      ]);
    } catch (\Throwable $e) {
      Log::error('Payment createTransaction error', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      if (!$request->expectsJson() && !$request->wantsJson()) {
        return redirect('/dashboard/transaksiBaru')->with('error', 'Gagal membuat transaksi Midtrans.');
      }

      return response()->json([
        'message' => 'Gagal membuat transaksi Midtrans.',
      ], 500);
    }
  }

  public function handleCallback(Request $request)
  {
    $payload = $request->all();

    Log::info('Midtrans callback payload', $payload);

    $orderId = (string) $request->input('order_id', '');
    $statusCode = (string) $request->input('status_code', '');
    $grossAmount = (string) $request->input('gross_amount', '');
    $signatureKey = (string) $request->input('signature_key', '');
    $transactionStatus = (string) $request->input('transaction_status', '');
    $fraudStatus = (string) $request->input('fraud_status', '');

    if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '' || $transactionStatus === '') {
      return response()->json(['message' => 'Payload callback tidak lengkap.'], 422);
    }

    $serverKey = (string) config('midtrans.server_key');
    $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    if (!hash_equals($computedSignature, $signatureKey)) {
      Log::warning('Midtrans callback signature mismatch', [
        'order_id' => $orderId,
      ]);

      return response()->json(['message' => 'Signature callback tidak valid.'], 403);
    }

    $transaction = Transaksi::find($orderId);
    if (!$transaction) {
      return response()->json(['message' => 'Transaksi tidak ditemukan.'], 404);
    }

    $transaction->is_paid_off = $this->mapPaidOffStatus($transactionStatus, $fraudStatus);
    if ($request->filled('payment_type')) {
      $transaction->metode_pembayaran = (string) $request->input('payment_type');
    }
    $transaction->save();

    return response()->json([
      'message' => 'Callback Midtrans diproses.',
      'order_id' => $orderId,
      'transaction_status' => $transactionStatus,
      'is_paid_off' => $transaction->is_paid_off,
    ]);
  }
}
