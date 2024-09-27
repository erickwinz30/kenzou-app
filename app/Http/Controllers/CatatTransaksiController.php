<?php

namespace App\Http\Controllers;

require_once base_path('vendor/autoload.php');

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Layanan;
use Twilio\Rest\Client;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailLayanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CatatTransaksiController extends Controller
{
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
        'total_harga' => 'required',
        'metode_pembayaran' => 'required',
      ]);

      $findPelanggan = Pelanggan::where('nomor_telepon', $validatedPhoneNumber['nomor_telepon'])->first();

      if ($findPelanggan) {
        $validatedData['pelanggan_id'] = $findPelanggan->id;
        Log::info('Found Pelanggan Number:', $findPelanggan->toArray());
      } else {
        $newPelanggan = Pelanggan::create(['nomor_telepon' => $validatedPhoneNumber['nomor_telepon']]);
        $findNewPelanggan = Pelanggan::where('nomor_telepon', $newPelanggan->nomor_telepon)->first();

        Log::info('Pelaggan Number Created', $newPelanggan->toArray());

        $validatedData['pelanggan_id'] = $findNewPelanggan->id;
      }

      $validatedData['user_id'] = Auth::user()->id;
      $validatedData['date'] = Carbon::now('Asia/Jakarta');

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

      foreach ($validatedData2['layanan_id'] as $layananId) {
        $detailLayanan = DetailLayanan::create([
          'transaksi_id' => $transaction->id, // UUID
          'layanan_id' => $layananId,
        ]);

        // Log each detail layanan
        Log::info('Detail Layanan Created:', ['detail_layanan' => $detailLayanan]);

        // return redirect('/transaksi')->with('success', 'Data transaksi telah ditambah!!!');
      }



      return redirect('/dashboard/transaksiBaru')->with('success', 'Data transaksi telah tertambah!!');
    } catch (\Exception $e) {
      // Log the error
      Log::error('Transaction Creation Error:', ['message' => $e->getMessage()]);

      return Redirect::back()->withErrors('Terjadi kesalahan saat mencatat transaksi.');
    }

    // return redirect()->with('success', 'Data transaksi telah tertambah!!');
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

        $data[] = [
          'nama' => $result->nama,
          'email' => $result->email,
          'nomor_telepon' => $result->nomor_telepon,
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
      Log::error('Error during Google sign-in: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return redirect()->route('register-next')->with('error', $errorMessage);
    }
  }

  public function dashboardKasir()
  {
    $todayTransaksi = $this->countMobil();
    $todaySales = $this->todaySales();
    $recentTransaction = $this->recentTransaction();
    // $dataPenjualanDashboard = $this->perHourSales($request);

    return view('dashboard.kasir.dashboard', [
      'todayTransaksi' => $todayTransaksi,
      'todaySales' => $todaySales,
      'recentTransactions' => $recentTransaction,
      // 'salesData' => $dataPenjualanDashboard,
    ]);
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

    $todaySales = Transaksi::whereDate('date', $today)->sum('total_harga');

    return $todaySales;
  }

  public function perHourSales(Request $request)
  {
    $currentDate = Carbon::now()->format('Y-m-d');

    $results = DB::table('transaksis')
      ->select(
        DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00') as hour"),
        DB::raw('SUM(total_harga) as total_harga')
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
          $totalHarga = $result->total_harga;
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
        'total_harga' => $totalHarga,
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

  private function sendMessage()
  {
    $sid    = env('TWILIO_SID');
    $token  = env('TWILIO_AUTH_TOKEN');
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create(
        "whatsapp:+6285155431948", // to
        array(
          "from" => "whatsapp:+14155238886",
          "body" => "Your Yummy Cupcakes Company order of 1 dozen frosted cupcakes has shipped and should be delivered on July 10, 2019. Details: http://www.yummycupcakes.com/",
        )
      );

    print($message->sid);
  }
}
