<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
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
    $pelanggans = Pelanggan::with('member')->get();

    return view('dashboard.pelanggan.index', [
      'pelanggans' => $pelanggans,
    ]);
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
      $dataPelanggan[] = [
        'id' => $pelanggan->id,
        'nomor_telepon' => $pelanggan->nomor_telepon,
        'nama' => $pelanggan->member->nama,
        'email' => $pelanggan->member->email,
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
      $dataPelanggan[] = [
        'id' => $pelanggan->id,
        'nomor_telepon' => $pelanggan->nomor_telepon,
        'nama' => "-",
        'email' => "-",
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
}
