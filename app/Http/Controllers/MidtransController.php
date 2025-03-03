<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
  public function __construct()
  {
    // Debugging: Output the server key (partially masked for security)
    $serverKey = config('midtrans.server_key');
    $maskedKey = substr($serverKey, 0, 4) . str_repeat('*', strlen($serverKey) - 8) . substr($serverKey, -4);
    Log::info('Midtrans Server Key (masked): ' . $maskedKey);

    // Set your Merchant Server Key
    Config::$serverKey = $serverKey;
    // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    Config::$isProduction = config('midtrans.is_production');
    // Set sanitization on (default)
    Config::$isSanitized = config('midtrans.is_sanitized');
    // Set 3DS transaction for credit card to true
    Config::$is3ds = config('midtrans.is_3ds');

    // Log the configuration
    Log::info('Midtrans Configuration:', [
      'isProduction' => Config::$isProduction,
      'isSanitized' => Config::$isSanitized,
      'is3ds' => Config::$is3ds,
    ]);
  }

  public function createTransaction(Request $request)
  {
    try {
      $transaction = Transaksi::findOrFail($request->transaction_id);

      // Config::$serverKey = config('midtrans.server_key');
      // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
      // Config::$isProduction = config('midtrans.is_production');
      // // Set sanitization on (default)
      // Config::$isSanitized = config('midtrans.is_sanitized');
      // // Set 3DS transaction for credit card to true
      // Config::$is3ds = config('midtrans.is_3ds');

      $itemDetails = [];
      foreach ($transaction->detail_layanan as $detail) {
        $layanan = $detail->layanan;
        $itemDetails[] = [
          'id' => $layanan->id,
          'price' => $layanan->harga,
          'quantity' => 1,
          'name' => $layanan->nama_layanan,
        ];
      }

      $params = [
        'transaction_details' => [
          'order_id' => $transaction->id,
          'gross_amount' => $transaction->subtotal,
        ],
        'item_details' => $itemDetails,
        'customer_details' => [
          'first_name' => explode(' ', $transaction->pelanggan->member->nama)[0],
          'email' => $transaction->pelanggan->member->email,
          'phone' => $transaction->pelanggan->nomor_telepon,
        ],
        'enabled_payments' => ['qris'],
      ];

      Log::info('Midtrans Transaction Params:', $params);

      $snapToken = Snap::getSnapToken($params);
      Log::info('Midtrans Snap Token generated: ' . $snapToken);

      // return response()->json(['snap_token' => $snapToken]);
      return redirect('/midtrans/qris/' . $snapToken);
    } catch (\Exception $e) {
      Log::error('Transaction Creation Error: ' . $e->getMessage());
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  // ... (rest of the methods remain unchanged)
}
