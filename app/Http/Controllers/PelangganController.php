<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      // $pelanggans = Pelanggan::with('member')->get();

      // return view('dashboard.pelanggan.index', [
      //   'pelanggans' => $pelanggans,
      // ]);

      $pelanggans = Pelanggan::with('member')->orderBy('created_at', 'desc')->get();

      // Ambil data preferensi layanan untuk setiap pelanggan
      foreach ($pelanggans as $pelanggan) {
        $layananTeratas = DB::table('detail_layanans')
          ->join('transaksis', 'detail_layanans.transaksi_id', '=', 'transaksis.id')
          ->join(
            'layanans',
            'detail_layanans.layanan_id',
            '=',
            'layanans.id'
          )
          ->where('transaksis.pelanggan_id', $pelanggan->id)
          ->select('layanans.nama_layanan', DB::raw('count(detail_layanans.layanan_id) as jumlah_penggunaan'))
          ->groupBy('layanans.nama_layanan')
          ->orderBy('jumlah_penggunaan', 'desc')
          ->limit(2)
          ->get();

        // Tambahkan data preferensi ke objek pelanggan
        $pelanggan->preferences = $layananTeratas;
      }

      return view('dashboard.pelanggan.index', [
        'pelanggans' => $pelanggans,
      ]);
    } catch (\Exception $e) {
      Log::error("Error fetching pelanggan data: " . $e->getMessage());
      return redirect('/dashboard/pelanggan')->with('error', 'Terjadi kesalahan saat mengambil data pelanggan.');
    }
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Pelanggan $pelanggan)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Pelanggan $pelanggan)
  {
    return view('dashboard.pelanggan.edit', [
      'pelanggan' => $pelanggan,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Pelanggan $pelanggan)
  {
    try {
      if (Auth::user()->is_admin !== 1) {
        return view('error-404-dashboard');
      }

      $member = $pelanggan->member;

      if ($request->nama !== $member->nama) {
        $rules['nama'] = 'required|min:3|max:255';
      }

      if (!$member->google_id) {
        if ($request->email !== $member->email) {
          $rules['email'] = 'required|min:5|max:255|unique:members|email:dns';
        }
      }

      if ($request->nomor_telepon !== $member->nomor_telepon) {
        $rules['nomor_telepon'] = 'required|min:10|max:15|unique:members';
      }

      if (date('Y-m-d', strtotime($request->tanggal_lahir)) !== date('Y-m-d', strtotime($member->tanggal_lahir))) {
        $rules['tanggal_lahir'] = 'required|date';
      }

      if ($request->experiece_point !== $member->experience_point) {
        $rules['experience_point'] = 'required|numeric';
      }

      if ($request->redeemable_point !== $member->redeemable_point) {
        $rules['redeemable_point'] = 'required|numeric';
      }

      $validatedData = $request->validate($rules);

      Member::where('id', $member->id)->update($validatedData);

      return redirect('/dashboard/pelanggan')->with('success', 'Data pelanggan telah diubah!');
    } catch (\Exception $e) {
      Log::info("Update Error: " . $e->getMessage());
      Log::info("Update Error: " . $e->getTraceAsString());
      return redirect('/dashboard/pelanggan')->with('error', 'Data pelanggan gagal diubah!');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Pelanggan $pelanggan)
  {
    try {
      if ($pelanggan->member_id) {
        Member::destroy($pelanggan->member->id);
      }
      // Pelanggan::destroy($pelanggan->id);
      Pelanggan::where('id', $pelanggan->id)->update(['member_id' => null]);

      return redirect('/dashboard/pelanggan')->with('success', 'Data pelanggan telah dihapus!');
    } catch (\Exception $e) {
      Log::info("Delete Error: " . $e->getMessage());
      Log::info("Delete Error: " . $e->getTraceAsString());
      return redirect('/dashboard/pelanggan')->with('error', 'Data pelanggan gagal dihapus!');
    }
  }

  public function memberFetch(Request $request)
  {
    $dataPelanggan = [];
    $pelanggans = Pelanggan::with('member')->whereNotNull('member_id')->get();

    foreach ($pelanggans as $pelanggan) {
      $umur = Carbon::parse($pelanggan->member->tanggal_lahir)->age;

      $layananTeratas = DB::table('detail_layanans')
        ->join('transaksis', 'detail_layanans.transaksi_id', '=', 'transaksis.id')
        ->join(
          'layanans',
          'detail_layanans.layanan_id',
          '=',
          'layanans.id'
        )
        ->where('transaksis.pelanggan_id', $pelanggan->id)
        ->select('layanans.nama_layanan', DB::raw('count(detail_layanans.layanan_id) as jumlah_penggunaan'))
        ->groupBy('layanans.nama_layanan')
        ->orderBy('jumlah_penggunaan', 'desc')
        ->limit(2)
        ->get();

      $dataPelanggan[] = [
        'id' => $pelanggan->id,
        'nomor_telepon' => $pelanggan->nomor_telepon,
        'nama' => $pelanggan->member->nama,
        'email' => $pelanggan->member->email,
        'preferences' => $layananTeratas,
        'tanggal_lahir' => Carbon::parse($pelanggan->member->tanggal_lahir)->format('Y-m-d'),
        'umur' => $umur,
        'experience_point' => $pelanggan->member->experience_point,
        'redeemable_point' => $pelanggan->member->redeemable_point,
        'referral_code' => $pelanggan->member->referral_code,
      ];
    }
    // echo ($dataPelanggan);

    if ($request->wantsJson()) {
      if ($pelanggans->isEmpty()) {
        return response()->json([]);
      } else {
        return response()->json($dataPelanggan, 200);
      }
    }

    return $dataPelanggan;
  }

  public function pelangganFetch(Request $request)
  {
    $dataPelanggan = [];
    $pelanggans = Pelanggan::with('member')->whereNull('member_id')->get();

    foreach ($pelanggans as $pelanggan) {
      $layananTeratas = DB::table('detail_layanans')
        ->join('transaksis', 'detail_layanans.transaksi_id', '=', 'transaksis.id')
        ->join(
          'layanans',
          'detail_layanans.layanan_id',
          '=',
          'layanans.id'
        )
        ->where('transaksis.pelanggan_id', $pelanggan->id)
        ->select('layanans.nama_layanan', DB::raw('count(detail_layanans.layanan_id) as jumlah_penggunaan'))
        ->groupBy('layanans.nama_layanan')
        ->orderBy('jumlah_penggunaan', 'desc')
        ->limit(2)
        ->get();

      $dataPelanggan[] = [
        'id' => $pelanggan->id,
        'nomor_telepon' => $pelanggan->nomor_telepon,
        'nama' => "-",
        'email' => "-",
        'preferences' => $layananTeratas,
        'tanggal_lahir' => "-",
        'umur' => "-",
        'experience_point' => "-",
        'redeemable_point' => "-",
        'referral_code' => "-",
      ];
    }
    // echo ($dataPelanggan);

    if ($request->wantsJson()) {
      if ($pelanggans->isEmpty()) {
        return response()->json([]);
      } else {
        return response()->json($dataPelanggan, 200);
      }
    }

    return $dataPelanggan;
  }

  public function memberPreference()
  {
    try {
      $customers = Pelanggan::all();

      $preferences = [];

      foreach ($customers as $customer) {
        // Query untuk menghitung jumlah penggunaan setiap layanan oleh member
        $layananTeratas = DB::table('detail_layanans')
          ->join('transaksis', 'detail_layanans.transaksi_id', '=', 'transaksis.id')
          ->join('layanans', 'detail_layanans.layanan_id', '=', 'layanans.id')
          ->where('transaksis.pelanggan_id', $customer->id)
          ->select('layanans.nama_layanan', DB::raw('count(detail_layanans.layanan_id) as jumlah_penggunaan'))
          ->groupBy('layanans.nama_layanan')
          ->orderBy('jumlah_penggunaan', 'desc')
          ->limit(2)
          ->get();

        $preferences[] = [
          'customer_id' => $customer->id,
          'customer_name' => $customer->member ? $customer->member->nama : $customer->nomor_telepon,
          'preferences' => $layananTeratas
        ];
      }

      // Return data dalam format JSON
      return response()->json($preferences, 200);
    } catch (\Exception $e) {
      Log::error("Error fetching member preference: " . $e->getMessage());
      return response()->json(['error' => 'Terjadi kesalahan saat mengambil data preferensi layanan.'], 500);
    }
  }

  public function allLayananCount(Request $request)
  {
    // Query for total sales per month
    $results = DB::table('detail_layanans')
      ->join('layanans', 'detail_layanans.layanan_id', '=', 'layanans.id')
      ->join('transaksis', 'detail_layanans.transaksi_id', '=', 'transaksis.id')
      ->select(
        'layanans.nama_layanan',
        DB::raw('COUNT(detail_layanans.id) AS jumlah_penggunaan'),
      )
      ->groupBy('layanans.nama_layanan')
      ->orderBy('jumlah_penggunaan', 'desc')
      ->get();


    $data = [];

    foreach ($results as $result) {
      $data[] = [
        'nama_layanan' => $result->nama_layanan,
        'jumlah_penggunaan' => $result->jumlah_penggunaan
      ];
    }

    Log::info('Data Layanan: ', ['data' => $data]);

    if ($request->wantsJson()) {
      return response()->json($data);
    }

    return $data;
  }
}
