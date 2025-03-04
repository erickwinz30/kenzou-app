<?php

namespace App\Http\Controllers;

// require_once base_path('vendor/autoload.php');

use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Member;
use App\Models\Layanan;
use Twilio\Rest\Client;
use App\Models\PointLog;
use App\Models\Challenge;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\OwnedVoucher;
use Illuminate\Http\Request;
use App\Models\DetailLayanan;
use App\Models\BadgeLeaderboard;
use App\Models\ChallengeProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CatatTransaksiController extends Controller
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

  public function index()
  {
    return view('dashboard.kasir.transaksi', [
      'layanans' => Layanan::all(),
      'tanggal_transaksi' => Carbon::now('Asia/Jakarta')->format('d-m-Y'),
    ]);
  }

  public function layanan()
  {
    return view('dashboard.kasir.layanan', [
      'layanans' => Layanan::all(),
    ]);
  }

  public function catat(Request $request)
  {
    try {
      $validatedPhoneNumber = $request->validate([
        'nomor_telepon' => 'required|min:10|max:15',
      ]);

      $validatedData = $request->validate([
        'keterangan' => 'max:255',
        'nomor_polisi' => 'max:12',
        'total' => 'required',
        'subtotal' => 'required',
        'metode_pembayaran' => 'required',
        'is_paid_off' => 'nullable|boolean',
      ]);

      $findPelanggan = Pelanggan::where('nomor_telepon', $validatedPhoneNumber['nomor_telepon'])->first();

      //check if pelanggan exists
      $validatedData = $this->checkRegisteredPelanggan($findPelanggan, $validatedPhoneNumber, $validatedData);


      $validatedData['user_id'] = Auth::user()->id;
      $validatedData['date'] = Carbon::now('Asia/Jakarta');

      if ($request->has('badge_id')) {
        $validatedData['badge_id'] = $request->badge_id;
      }

      if ($request->has('leaderboard_id')) {
        $validatedData['leaderboard_id'] = $request->leaderboard_id;
      }

      if ($request->has('voucher_id')) {
        $validatedData['voucher_id'] = $request->voucher_id;
      }

      $challengeProgressPoint = null;

      if ($request->has('challenge_id')) {
        $validatedData['challenge_id'] = $request->challenge_id;
      }

      // Log validated data
      Log::info('Validated Data:', $validatedData);

      // Create new transaction
      $transaction = Transaksi::create($validatedData);
      // Log created transaction
      Log::info('Created Transaction:', $transaction->toArray());

      //catat detail layanan
      $validatedData2 = $request->validate([
        'layanan_id' => 'required|array',
        'layanan_id.*' => 'exists:layanans,id',
      ]);

      Log::info('Detail Layanan Created:', ['detail_layanan' => $validatedData2]);

      $totalPoint = 0;

      foreach ($validatedData2['layanan_id'] as $layananId) {
        $detailLayanan = DetailLayanan::create([
          'transaksi_id' => $transaction->id, // UUID
          'layanan_id' => $layananId,
        ]);
        // Log each detail layanan
        Log::info('Detail Layanan Created:', ['detail_layanan' => $detailLayanan]);

        $layananPoint = Layanan::where('id', $layananId)->first()->point;
        $totalPoint = $totalPoint + $layananPoint;
        Log::info('Layanan Point:', ['point' => $totalPoint]);
      }

      // gamification
      //check if member exists
      // if ($findPelangganAgain) {
      //   if ($findPelangganAgain->member_id) {
      //     if (isset($validatedData['challenge_id'])) {
      //       // ChallengeProgress::where('id', $validatedData['challenge_progress_id'])->update(['is_used' => true]);

      //       Log::info('Challenge Progress ID:', ['id' => $validatedData['challenge_id']]);

      //       $updated = ChallengeProgress::where('challenge_id', $validatedData['challenge_id'])->where('member_id', $findPelangganAgain->member_id)->update(['is_used' => true]);

      //       if ($updated) {
      //         Log::info('Challenge Progress updated successfully.');
      //       } else {
      //         Log::warning('Challenge Progress update failed. No record found with the given ID.');
      //       }
      //     } else {
      //       Log::info('Challenge Progress ID on validated is null');
      //       $allChallengeProgress = ChallengeProgress::where('member_id', $findPelangganAgain->member_id)->where('is_completed', false)->get();
      //       Log::info('All Challenge Progress:', ['progress' => $allChallengeProgress]);

      //       if ($allChallengeProgress) {
      //         Log::info('Challenge Progress found for member.');
      //         foreach ($allChallengeProgress as $challengeProgress) {
      //           if ($challengeProgress->challenge->is_active) {
      //             Log::info('Challenge ' . $challengeProgress->challenge->description . ' is active.');
      //             if ($challengeProgress->is_completed == false) {
      //               Log::info('Challenge ' . $challengeProgress->challenge->description . ' is not completed.');
      //               $challengeUnit = $challengeProgress->challenge->unit;

      //               if ($challengeUnit === "Transaksi") {
      //                 $transactionProgress = $challengeProgress->progress + 1;

      //                 if ($transactionProgress === $challengeProgress->challenge->target) {
      //                   $challengeProgress->update(['progress' => $transactionProgress, 'is_completed' => true, 'completed_at' => Carbon::now('Asia/Jakarta')]);

      //                   $checkChallengeRepeat = Challenge::where('id', $challengeProgress->challenge->id)->first()->is_repeatable;
      //                   if ($checkChallengeRepeat) {
      //                     ChallengeProgress::create([
      //                       'member_id' => $findPelangganAgain->member_id,
      //                       'challenge_id' => $challengeProgress->challenge->id,
      //                     ]);

      //                     Log::info('Challenge repeated for member who finish the challenge.');
      //                   }
      //                 } else {
      //                   $challengeProgress->update(['progress' => $transactionProgress]);
      //                 }
      //               } else if ($challengeUnit === "Total Pengeluaran Member") {
      //                 $totalPengeluaran = $transaction->subtotal;
      //                 $progress = $challengeProgress->progress + $totalPengeluaran;

      //                 if ($progress >= $challengeProgress->challenge->target) {
      //                   $challengeProgress->update(['progress' => $progress, 'is_completed' => true, 'completed_at' => Carbon::now('Asia/Jakarta')]);

      //                   $checkChallengeRepeat = Challenge::where('id', $challengeProgress->challenge->id)->first()->is_repeatable;
      //                   if ($checkChallengeRepeat) {
      //                     ChallengeProgress::create([
      //                       'member_id' => $findPelangganAgain->member_id,
      //                       'challenge_id' => $challengeProgress->challenge->id,
      //                     ]);

      //                     Log::info('Challenge repeated for member who finish the challenge.');
      //                   }
      //                 } else {
      //                   $challengeProgress->update(['progress' => $progress]);
      //                 }
      //               }
      //             }
      //           }
      //         }
      //       }
      //     }

      //     if (isset($validatedData['voucher_id'])) {
      //       Log::info('Voucher ID:', ['id' => $validatedData['voucher_id']]);

      //       $this->claimVoucher($validatedData['voucher_id'], $findPelangganAgain->member_id);
      //     }
      //     $this->storeNewPoint($transaction, $findPelangganAgain->member_id, $challengeProgressPoint, $totalPoint);
      //   } else {
      //     Log::info('Old customer without member and need to send invoice through WhatsApp.');

      //     $transactionData = [];
      //     $transactionLayananData = [];
      //     $layanans = Layanan::whereIn('id', $validatedData2['layanan_id'])->get();
      //     Log::info('Layanan:', ['layanans' => $layanans]);

      //     $transactionData['transactionId'] = $transaction->id;
      //     $transactionData['phoneNumber'] = $validatedPhoneNumber['nomor_telepon'];
      //     $transactionData['subtotal'] = $validatedData['subtotal'];
      //     $transactionData['layanans'] = $validatedData2['layanan_id'];
      //     $transactionData['date'] = $transaction->date;

      //     foreach ($layanans as $layanan) {
      //       $transactionLayananData[] = [
      //         'nama_layanan' => $layanan->nama_layanan,
      //         'harga' => $layanan->harga,
      //       ];
      //     }

      //     $this->sendMessage($transactionData, $transactionLayananData);
      //   }
      // }

      if ($transaction->metode_pembayaran === "qris") {
        return redirect()->route('transaction-confirmation', ['id' => $transaction->id]);
      } else {
        return redirect('/dashboard/transaksiBaru')->with('success', 'Data transaksi telah tertambah, pastikan transaksi sudah lunas!!');
      }
    } catch (\Exception $e) {
      // Log the error
      Log::error('Transaction Creation Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

      return redirect()->back()->with('error', 'Terjadi kesalahan saat mencatat transaksi.');
    }

    // return redirect()->with('success', 'Data transaksi telah tertambah!!');
  }

  public function memberBenefit($transactionId)
  {
    $transaction = Transaksi::where('id', $transactionId)->first();
    //check again if pelanggan exists just in case the previous phone number is not member
    $findPelangganAgain = Pelanggan::where('nomor_telepon', $transaction->pelanggan->nomor_telepon)->first();

    $totalPoint = 0;
    $layanans = $transaction->detail_layanan ?? collect(); // Ensure $layanans is not null
    Log::info('Layanan:', ['layanans' => $layanans]);
    foreach ($layanans as $layanan) {
      $totalPoint += $layanan->layanan->point;
    }

    // Log total points calculated
    Log::info('Total Points Calculated:', ['totalPoint' => $totalPoint]);

    //check if member exists
    if ($findPelangganAgain) {
      if ($findPelangganAgain->member_id) {
        $challengeProgressPoint = null;
        if (isset($transaction->challenge_id)) {
          Log::info('Challenge Progress ID:', ['id' => $transaction->challenge_id]);

          $updated = ChallengeProgress::where('challenge_id', $transaction->challenge_id)
            ->where('member_id', $findPelangganAgain->member_id)
            ->update(['is_used' => true]);
          $challengeProgressPoint = 15;

          if ($updated) {
            Log::info('Challenge Progress updated successfully.');
          } else {
            Log::warning('Challenge Progress update failed. No record found with the given ID.');
          }
        } else {
          Log::info('Challenge Progress ID on validated is null');
          $allChallengeProgress = ChallengeProgress::where('member_id', $findPelangganAgain->member_id)
            ->where('is_completed', false)
            ->get();
          Log::info('All Challenge Progress:', ['progress' => $allChallengeProgress]);

          if ($allChallengeProgress) {
            Log::info('Challenge Progress found for member.');
            foreach ($allChallengeProgress as $challengeProgress) {
              if ($challengeProgress->challenge->is_active) {
                Log::info('Challenge ' . $challengeProgress->challenge->description . ' is active.');
                if ($challengeProgress->is_completed == false) {
                  Log::info('Challenge ' . $challengeProgress->challenge->description . ' is not completed.');
                  $challengeUnit = $challengeProgress->challenge->unit;

                  if ($challengeUnit === "Transaksi") {
                    $transactionProgress = $challengeProgress->progress + 1;

                    if ($transactionProgress === $challengeProgress->challenge->target) {
                      $challengeProgress->update(['progress' => $transactionProgress, 'is_completed' => true, 'completed_at' => Carbon::now('Asia/Jakarta')]);

                      $checkChallengeRepeat = Challenge::where('id', $challengeProgress->challenge->id)->first()->is_repeatable;
                      if ($checkChallengeRepeat) {
                        ChallengeProgress::create([
                          'member_id' => $findPelangganAgain->member_id,
                          'challenge_id' => $challengeProgress->challenge->id,
                        ]);

                        Log::info('Challenge repeated for member who finish the challenge.');
                      }
                    } else {
                      $challengeProgress->update(['progress' => $transactionProgress]);
                    }
                  } else if ($challengeUnit === "Total Pengeluaran Member") {
                    $totalPengeluaran = $transaction->subtotal;
                    $progress = $challengeProgress->progress + $totalPengeluaran;

                    if ($progress >= $challengeProgress->challenge->target) {
                      $challengeProgress->update(['progress' => $progress, 'is_completed' => true, 'completed_at' => Carbon::now('Asia/Jakarta')]);

                      $checkChallengeRepeat = Challenge::where('id', $challengeProgress->challenge->id)->first()->is_repeatable;
                      if ($checkChallengeRepeat) {
                        ChallengeProgress::create([
                          'member_id' => $findPelangganAgain->member_id,
                          'challenge_id' => $challengeProgress->challenge->id,
                        ]);

                        Log::info('Challenge repeated for member who finish the challenge.');
                      }
                    } else {
                      $challengeProgress->update(['progress' => $progress]);
                    }
                  }
                }
              }
            }
          }
        }

        if (isset($transaction->voucher_id)) {
          Log::info('Voucher ID:', ['id' => $transaction->voucher_id]);

          $this->claimVoucher($transaction->voucher_id, $findPelangganAgain->member_id);
        }
        $this->storeNewPoint($transaction, $findPelangganAgain->member_id, $challengeProgressPoint, $totalPoint);
      } else {
        Log::info('Old customer without member and need to send invoice through WhatsApp.');

        $transactionData = [];
        $transactionLayananData = [];
        $totalPoint = 0;

        $layanans = Layanan::whereIn('id', $transaction->detailLayanans->pluck('layanan_id'))->get();
        Log::info('Layanan:', ['layanans' => $layanans]);

        $transactionData['transactionId'] = $transaction->id;
        $transactionData['phoneNumber'] = $transaction->pelanggan->nomor_telepon;
        $transactionData['subtotal'] = $transaction->subtotal;
        $transactionData['layanans'] = $transaction->detailLayanans->pluck('layanan_id');
        $transactionData['date'] = $transaction->date;

        foreach ($layanans as $layanan) {
          $transactionLayananData[] = [
            'nama_layanan' => $layanan->nama_layanan,
            'harga' => $layanan->harga,
          ];
        }

        $this->sendMessage($transactionData, $transactionLayananData);
      }
    }

    return redirect('/dashboard/transaksiBaru')->with('success', 'Data transaksi telah tertambah dan pembayaran telah terkonfirmasi!');
  }

  public function viewConfirmationPaidOff($id)
  {
    $transaction = Transaksi::where('id', $id)->first();
    return view('dashboard.kasir.confirmation', [
      'id' => $transaction->id,
    ]);
  }

  public function confirmPaidOff(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'id' => 'required',
        'is_paid_off' => 'required|boolean',
      ]);

      $transaction = Transaksi::findOrFail($validatedData['id']);
      $transaction->is_paid_off = $validatedData['is_paid_off'];
      $transaction->save();

      $this->memberBenefit($transaction->id);

      if ($transaction->is_paid_off == true) {
        return redirect('/dashboard/transaksiBaru')->with('success', 'Transaksi telah dikonfirmasi.');
      } else {
        return redirect('/dashboard/transaksiBaru')->with('error', 'Transaksi belum lunas. Silahkan konfirmasi ke pelanggan!');
      }
    } catch (\Exception $e) {
      Log::error('Transaction Confirmation Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
      return redirect()->back()->with('error', 'Terjadi kesalahan saat konfirmasi transaksi.');
    }
  }

  private function checkRegisteredPelanggan($findPelanggan, $validatedPhoneNumber, $validatedData)
  {
    if ($findPelanggan) {
      $validatedData['pelanggan_id'] = $findPelanggan->id;
      Log::info('Found Pelanggan Number:', $findPelanggan->toArray());
    } else {
      $newPelanggan = Pelanggan::create(['nomor_telepon' => $validatedPhoneNumber['nomor_telepon']]);
      $findNewPelanggan = Pelanggan::where('nomor_telepon', $newPelanggan->nomor_telepon)->first();

      Log::info('Pelanggan Number Created', $newPelanggan->toArray());

      $validatedData['pelanggan_id'] = $findNewPelanggan->id;

      //tambahkan sendMessage untuk pelanggan yang bukan member
    }

    return $validatedData;
  }

  private function storeNewPoint($transaction, $memberId, $challengePoint, $totalPoint)
  {
    if (!is_numeric($totalPoint)) {
      Log::error('Non-numeric value passed to increment method.', ['totalPoint' => $totalPoint]);
      throw new \InvalidArgumentException('Total point must be a numeric value.');
    }

    if ($challengePoint) {
      $totalPoint = $totalPoint + $challengePoint;
    }

    Member::where('nomor_telepon', $transaction->pelanggan->nomor_telepon)->increment('experience_point', $totalPoint);
    Member::where('nomor_telepon', $transaction->pelanggan->nomor_telepon)->increment('redeemable_point', $totalPoint);

    $pointLog = PointLog::create([
      'member_id' => $memberId,
      'point' => $totalPoint,
      'transaksi_id' => $transaction->id,
      'status' => $challengePoint ? 'Transaksi dan menggunakan challenge' : 'Transaksi',
      'date' => Carbon::now('Asia/Jakarta'),
      'is_increase' => true,
    ]);

    Log::info('Point Log Created:', $pointLog->toArray());
  }

  private function claimVoucher($voucherId, $memberId)
  {
    $voucher = OwnedVoucher::where('member_id', $memberId)->where('voucher_id', $voucherId)->first();

    if ($voucher) {
      $voucher->update([
        'is_used' => true,
        'used_date' => Carbon::now('Asia/Jakarta')
      ]);
    }
  }

  public function searchPhoneNumber(Request $request)
  {
    try {
      $nomor_telepon = $request->nomor_telepon;
      Log::info('Input Nomor Telepon:', ['search_result' => $nomor_telepon]);

      $searchResult = Member::where('nomor_telepon', 'like', '%' . $nomor_telepon . '%')->get();

      $data = [];

      foreach ($searchResult as $result) {
        Log::info('Search Result:', ['search_result' => $result]);
        $memberRankCheck = $this->checkMemberRank($result->nomor_telepon);

        $badge = DB::table('badges')
          ->where('min_point', '<=', $result->experience_point)
          ->where('max_point', '>=', $result->experience_point)
          ->where('is_active', true)
          ->first();

        $data[] = [
          'nama' => $result->nama,
          'email' => $result->email,
          'nomor_telepon' => $result->nomor_telepon,
          'badgeId' => $badge->id,
          'badgeName' => $badge->nama,
          'badgeDiscount' => $badge->discount,
          'rankId' => $memberRankCheck['id'] ?? null,
          'rank' => $memberRankCheck['rank'] ?? null,
          'rankDiscount' => $memberRankCheck['discount'] ?? 0,
        ];
      }

      if ($request->wantsJson()) {
        // Pastikan kita mengembalikan array kosong jika tidak ada hasil
        if ($searchResult->isEmpty()) {
          return response()->json([]);
        } else {
          return response()->json($data, 200);
        }
      }

      return $data;
      // Log::info('Search Result:', ['search_result' => $searchResult]);
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error('Pencarian pelanggan error: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return redirect('/dashboard/transaksiBaru')->with('error', $errorMessage);
    }
  }

  public function countMobil()
  {
    $today = Carbon::today()->toDateString();

    $todayTransaksi = Transaksi::whereDate('date', $today)->count();

    return $todayTransaksi;
  }

  public function todaySales()
  {
    $today = Carbon::today()->toDateString();

    $todaySales = Transaksi::whereDate('date', $today)->sum('subtotal');

    return $todaySales;
  }

  public function perHourSales(Request $request)
  {
    $currentDate = Carbon::now()->format('Y-m-d');

    $results = DB::table('transaksis')
      ->select(
        DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00') as hour"),
        DB::raw('SUM(subtotal) as subtotal')
      )
      ->whereDate('date', $currentDate) // Filter by current date
      ->whereTime('date', '>=', '07:30:00')
      ->whereTime('date', '<=', '17:30:00')
      ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00')"))
      ->orderBy('hour')
      ->get();

    $results2 = DB::table('transaksis')
      ->select(
        DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00') as hour"),
        DB::raw('COUNT(id) as transaksi_id')
      )
      ->whereDate('date', $currentDate) // Filter by current date
      ->whereTime('date', '>=', '07:30:00')
      ->whereTime('date', '<=', '17:30:00')
      ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00')"))
      ->orderBy('hour')
      ->get();

    $data = [];
    $startHour = Carbon::createFromTimeString('07:00:00');
    $endHour = Carbon::createFromTimeString('17:00:00');
    $currentHour = $startHour->copy();

    while ($currentHour->lte($endHour)) {
      $hourString = $currentHour->format('Y-m-d H:00:00');
      $totalHarga = 0;
      $transactionCount = 0;

      foreach ($results as $result) {
        if ($result->hour === $hourString) {
          $totalHarga = $result->subtotal;
          break;
        }
      }

      foreach ($results2 as $result) {
        if ($result->hour === $hourString) {
          $transactionCount = $result->transaksi_id;
          break;
        }
      }

      $data[] = [
        'hour' => $hourString,
        'subtotal' => $totalHarga,
        'jumlah_transaksi' => $transactionCount,
      ];

      $currentHour->addHour();
    }

    if ($request->wantsJson()) {
      return response()->json($data);
    }

    return $data;
  }

  public function recentTransaction()
  {
    $recentTransaction = Transaksi::orderBy('date', 'desc')->take(8)->get();

    return $recentTransaction;
  }

  private function sendMessage($transactionData, $transactionLayananData)
  {
    // Buat pesan invoice
    $body = "Invoice Transaksi\n";
    $body .= "=================\n";
    $body .= "ID Transaksi: {$transactionData['transactionId']}\n";
    $body .= "No.Telp Pelanggan: {$transactionData['phoneNumber']}\n";
    $body .= "Tanggal: {$transactionData['date']->format('Y-m-d')}\n";
    $body .= "-----------------\n";
    $body .= "Layanan yang Dipilih:\n";

    foreach ($transactionLayananData as $layanan) {
      $body .= "- {$layanan['nama_layanan']}: Rp. " . number_format($layanan['harga'], 0, ',', '.') . "\n";
    }

    $body .= "-----------------\n";
    $body .= "Subtotal: Rp. " . number_format($transactionData['subtotal'], 0, ',', '.') . "\n";
    $body .= "=================\n";
    $body .= "Terima kasih telah menggunakan layanan kami.\n";


    // using twillio
    $sid    = env('TWILIO_SID');
    $token  = env('TWILIO_AUTH_TOKEN');
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create(
        "whatsapp:+6285155431948", // to
        array(
          "from" => "whatsapp:+14155238886",
          "body" => $body,
        )
      );

    print($message->sid);

    // $sid    = env('TWILIO_SID');
    // $token  = env('TWILIO_AUTH_TOKEN');
    // $twilio = new Client($sid,
    //   $token
    // );

    // $message = $twilio->messages
    // ->create(
    //   "whatsapp:+6285155431948", // to
    //   array(
    //     "from" => "whatsapp:+14155238886",
    //     "body" => "Your Yummy Cupcakes Company order of 1 dozen frosted cupcakes has shipped and should be delivered on July 10, 2019. Details: http://www.yummycupcakes.com/",
    //   )
    // );

    // print($message->sid);
  }

  public function checkMemberVoucher(Request $request)
  {
    try {
      $memberId = Member::where('nomor_telepon', $request->nomor_telepon)->first()->id;
      Log::info('Input Nomor Telepon:', ['Member Phone Number' => $memberId]);

      $listVoucher = OwnedVoucher::where('member_id', $memberId)->where('is_used', false)
        ->whereHas('voucher', function ($query) {
          $query->where('is_active', true)
            ->where('from_date', '<=', Carbon::now())
            ->where('to_date', '>=', Carbon::now());
        })->get();
      Log::info('Owned Voucher:', ['Voucher' => $listVoucher]);

      $data = [];

      if ($listVoucher) {
        foreach ($listVoucher as $ownedVoucher) {
          Log::info('List Owned Voucher:', ['Voucher' => $ownedVoucher]);

          $data[] = [
            'id' => $ownedVoucher->voucher->id,
            'name' => $ownedVoucher->voucher->nama,
            'description' => $ownedVoucher->voucher->description,
            'discount' => $ownedVoucher->voucher->discount,
            'minimum_transaction' => $ownedVoucher->voucher->minimum_transaction,
            'to_date' => Carbon::parse($ownedVoucher->voucher->to_date)->format('d M Y'),
          ];
        }
      }

      if ($request->wantsJson()) {
        // Pastikan kita mengembalikan array kosong jika tidak ada hasil
        if ($listVoucher->isEmpty()) {
          return response()->json([]);
        } else {
          return response()->json($data);
        }
      }

      return $data;
      // Log::info('Search Result:', ['search_result' => $searchResult]);
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error('Pencarian voucher member error: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return redirect('/dashboard/transaksiBaru')->with('error', $errorMessage);
    }
  }

  public function checkMemberChallenge(Request $request)
  {
    try {
      $memberId = Member::where('nomor_telepon', $request->nomor_telepon)->first()->id;
      Log::info('Input Nomor Telepon:', ['Member Phone Number' => $memberId]);

      $listChallenge = ChallengeProgress::where('member_id', $memberId)->where('is_completed', true)->where('is_used', false)
        ->whereHas('challenge', function ($query) {
          $query->where('is_active', true)
            ->where('from_date', '<=', Carbon::now())
            ->where('to_date', '>=', Carbon::now());
        })->get();
      Log::info('Owned Challenge:', ['Challenge' => $listChallenge]);

      $data = [];

      if ($listChallenge) {
        foreach ($listChallenge as $challengeProgress) {
          Log::info('List Progressed Challenge:', ['Challenge' => $challengeProgress]);

          $data[] = [
            'id' => $challengeProgress->challenge->id,
            'description' => $challengeProgress->challenge->description,
            'target' => $challengeProgress->challenge->target,
            'unit' => $challengeProgress->challenge->unit,
            'layanan_id' => $challengeProgress->challenge->layanan_id,
            'layanan_name' => $challengeProgress->challenge->layanan->nama_layanan,
            'layanan_price' => $challengeProgress->challenge->layanan->harga,
            'to_date' => Carbon::parse($challengeProgress->challenge->to_date)->format('d M Y'),
          ];
        }
      }

      if ($request->wantsJson()) {
        // Pastikan kita mengembalikan array kosong jika tidak ada hasil
        if ($listChallenge->isEmpty()) {
          return response()->json([]);
        } else {
          return response()->json($data);
        }
      }

      return $data;
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error('Pencarian challenge member error: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return redirect('/dashboard/transaksiBaru')->with('error', $errorMessage);
    }
  }

  public function checkMemberRank($phoneNumber)
  {
    try {
      $member = Member::where('nomor_telepon', $phoneNumber)->first();
      $leaderboards = Member::orderBy('experience_point', 'desc')->get();

      // Cari ranking member
      $memberRank = $leaderboards->search(function ($item) use ($member) {
        return $item->id == $member->id;
      });

      // Tambahkan 1 karena index dimulai dari 0
      $memberRank = $memberRank !== false ? $memberRank + 1 : null;
      Log::info('Member Rank:', ['Rank' => $memberRank]);

      $data = [];

      if ($memberRank === 1 || $memberRank === 2 || $memberRank === 3) {
        $rank = BadgeLeaderboard::where('rank', $memberRank)->where('is_active', true)->first();

        $data = [
          'id' => $rank->id,
          'rank' => $memberRank,
          'discount' => $rank->discount,
        ];

        Log::info('Member Data', ['Member Rank' => $data]);
      }

      return $data;
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error('Pencarian badge member error: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return redirect('/dashboard/transaksiBaru')->with('error', $errorMessage);
    }
  }

  public function showQris(Transaksi $transaksi, Request $request)
  {
    $snapToken = $request->query('snap_token');
    $clientKey = config('midtrans.client_key');
    return view('dashboard.kasir.qris', compact('transaction', 'snapToken', 'clientKey'));
  }
}
