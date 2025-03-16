<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use App\Models\Layanan;
use App\Models\PointLog;
use App\Models\Transaksi;
use App\Models\OwnedVoucher;
use Illuminate\Http\Request;
use App\Models\DetailLayanan;
use App\Models\ChallengeProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CatatTransaksiController; // Import the CatatTransaksiController

class TransaksiController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $today = Carbon::today()->toDateString();

    return view('dashboard.transaksi.index', [
      'transaksis' => Transaksi::with(['pelanggan', 'detail_layanan'])
        ->whereDate('date', $today)
        ->orderBy('date', 'desc')
        ->get(),
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
  public function show(Transaksi $transaksi)
  {
    $detailLayanan = DetailLayanan::where('transaksi_id', $transaksi->id)->get();
    Log::info('Detail Layanan:', ['layanan' => $detailLayanan]);

    return view('dashboard.transaksi.view', [
      'transaksi' => $transaksi,
      // 'detailLayanans' => $detailLayanan,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Transaksi $transaksi)
  {
    $ownedVouchers = OwnedVoucher::where('member_id', $transaksi->pelanggan->member_id)->where('is_used', false)
      ->whereHas('voucher', function ($query) {
        $query->where('is_active', true);
      })->get();
    $usedVoucher = OwnedVoucher::where('member_id', $transaksi->pelanggan->member_id)->where('voucher_id', $transaksi->voucher_id)->where('is_used', true)->first();
    $listChallenge = ChallengeProgress::where('member_id', $transaksi->pelanggan->member_id)->where('is_completed', true)->where('is_used', false)
      ->whereHas('challenge', function ($query) {
        $query->where('is_active', true);
      })->get();
    $usedChallenge = ChallengeProgress::where('member_id', $transaksi->pelanggan->member_id)->where('id', $transaksi->challenge_progress_id)->where('is_used', true)->first();
    Log::info('Used Challenge: ', ['challenge' => $usedChallenge]);

    return view('dashboard.transaksi.edit', [
      'transaksi' => $transaksi,
      'users' => User::all(),
      'layanans' => Layanan::all(),
      'detailLayanans' => DetailLayanan::where('transaksi_id', $transaksi->id)->get(),
      'ownedVouchers' => $ownedVouchers,
      'usedVoucher' => $usedVoucher,
      'listChallenge' => $listChallenge,
      'usedChallenge' => $usedChallenge,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Transaksi $transaksi)
  {
    try {
      $rules = [];

      if ($request->user_id !== $transaksi->user_id) {
        $rules['user_id'] = 'required';
      }

      if (date('Y-m-d\TH:i', strtotime($request->date)) !== date('Y-m-d\TH:i', strtotime($transaksi->date))) {
        $rules['date'] = 'required|date';
      }

      if ($request->metode_pembayaran !== $transaksi->metode_pembayaran) {
        $rules['metode_pembayaran'] = 'required';
      }

      if ($request->is_paid_off !== $transaksi->is_paid_off) {
        $rules['is_paid_off'] = 'required';

        // Call the memberBenefit function from CatatTransaksiController
        $catatTransaksiController = new CatatTransaksiController();
        $catatTransaksiController->memberBenefit($transaksi->id);
      }

      if ($request->nomor_polisi !== $transaksi->nomor_polisi) {
        $rules['nomor_polisi'] = 'max:12';
      }

      if ($request->keterangan !== $transaksi->keterangan) {
        $rules['keterangan'] = 'max:255';
      }

      if ($request->total != $transaksi->total) {
        $rules['total'] = 'required';
      }

      if ($request->subtotal != $transaksi->subtotal) {
        $rules['subtotal'] = 'required';
      }

      // 2. Handle mutual exclusivity
      $validatedDataTransaksi = $request->validate($rules);

      // Handle voucher_id
      if (!$request->has('voucher_id') || $request->voucher_id === null) {
        $validatedDataTransaksi['voucher_id'] = null;

        // Reset previous voucher if it exists
        $previousOwnedVoucher = OwnedVoucher::where('member_id', $transaksi->pelanggan->member_id)
          ->where('voucher_id', $transaksi->voucher_id)
          ->where('is_used', true)
          ->first();

        if ($previousOwnedVoucher) {
          $previousOwnedVoucher->update([
            'is_used' => false,
            'used_date' => null,
          ]);
          Log::info('Previous Owned Voucher Reset: ', ['owned_voucher' => $previousOwnedVoucher]);
        }
      } elseif ($request->voucher_id !== $transaksi->voucher_id) {
        $validatedDataTransaksi['voucher_id'] = $request->voucher_id;
        $validatedDataTransaksi['challenge_progress_id'] = null;

        // Reset previous challenge if it exists
        $previousChallengeProgress = ChallengeProgress::where('id', $transaksi->challenge_progress_id)
          ->where('is_used', true)
          ->first();

        if ($previousChallengeProgress) {
          $previousChallengeProgress->update([
            'is_used' => false,
          ]);
          Log::info('Previous Challenge Progress Reset: ', ['challenge_progress' => $previousChallengeProgress]);
        }

        // Reset previous voucher if it exists
        $previousOwnedVoucher = OwnedVoucher::where('member_id', $transaksi->pelanggan->member_id)
          ->where('voucher_id', $transaksi->voucher_id)
          ->where('is_used', true)
          ->first();

        if ($previousOwnedVoucher) {
          $previousOwnedVoucher->update([
            'is_used' => false,
            'used_date' => null,
          ]);
          Log::info('Previous Owned Voucher Reset: ', ['owned_voucher' => $previousOwnedVoucher]);
        }

        // Mark new voucher as used
        $updatedVoucher = OwnedVoucher::where('member_id', $transaksi->pelanggan->member_id)
          ->where('voucher_id', $request->voucher_id)
          ->first();

        if ($updatedVoucher) {
          $updatedVoucher->update([
            'is_used' => true,
            'used_date' => Carbon::now('Asia/Jakarta'),
          ]);
          Log::info('Voucher Updated: ', ['voucher' => $updatedVoucher]);
        }
      }

      // Handle challenge_progress_id
      if (!$request->has('challenge_progress_id') || $request->challenge_progress_id === null) {
        $validatedDataTransaksi['challenge_progress_id'] = null;

        // Reset previous challenge if it exists
        $previousChallengeProgress = ChallengeProgress::where('id', $transaksi->challenge_progress_id)
          ->where('is_used', true)
          ->first();

        if ($previousChallengeProgress) {
          $previousChallengeProgress->update([
            'is_used' => false,
          ]);
          Log::info('Previous Challenge Progress Reset: ', ['challenge_progress' => $previousChallengeProgress]);
        }
      } elseif ($request->challenge_progress_id !== $transaksi->challenge_progress_id) {
        $validatedDataTransaksi['challenge_progress_id'] = $request->challenge_progress_id;
        $validatedDataTransaksi['voucher_id'] = null;

        // Reset previous voucher if it exists
        $previousOwnedVoucher = OwnedVoucher::where('member_id', $transaksi->pelanggan->member_id)
          ->where('voucher_id', $transaksi->voucher_id)
          ->where('is_used', true)
          ->first();

        if ($previousOwnedVoucher) {
          $previousOwnedVoucher->update([
            'is_used' => false,
            'used_date' => null,
          ]);
          Log::info('Previous Owned Voucher Reset: ', ['owned_voucher' => $previousOwnedVoucher]);
        }

        // Reset previous challenge if it exists
        $previousChallengeProgress = ChallengeProgress::where('id', $transaksi->challenge_progress_id)
          ->where('is_used', true)
          ->first();

        if ($previousChallengeProgress) {
          $previousChallengeProgress->update([
            'is_used' => false,
          ]);
          Log::info('Previous Challenge Progress Reset: ', ['challenge_progress' => $previousChallengeProgress]);
        }

        // Mark new challenge as used
        $updatedChallengeProgress = ChallengeProgress::where('id', $request->challenge_progress_id)
          ->first();

        if ($updatedChallengeProgress) {
          $updatedChallengeProgress->update([
            'is_used' => true,
          ]);
          Log::info('Challenge Progress Updated: ', ['challenge_progress' => $updatedChallengeProgress]);
        }
      }

      $updatedTransaction = Transaksi::where('id', $transaksi->id)->update($validatedDataTransaksi);
      Log::info('Transaction Updated: ', ['transaction' => $updatedTransaction]);

      // Rest of the code remains the same
      $rules2 = [
        'layanan_id' => 'array',
        'layanan_id.*' => 'nullable|:layanans,id',
      ];

      $validatedDataLayanan = $request->validate($rules2);

      // Get existing detail layanan
      $existingDetailLayanan = DetailLayanan::where('transaksi_id', $transaksi->id)
        ->pluck('layanan_id')
        ->toArray();

      // Get new layanan ids (remove null values)
      $newLayananIds = array_filter($validatedDataLayanan['layanan_id']);

      // Find differences
      $layananToAdd = array_diff($newLayananIds, $existingDetailLayanan);
      $layananToRemove = array_diff($existingDetailLayanan, $newLayananIds);
      Log::info('Layanan to Add: ', ['layanan' => $layananToAdd]);
      Log::info('Layanan to Remove: ', ['layanan' => $layananToRemove]);

      // Remove unused layanan
      if (!empty($layananToRemove)) {
        DetailLayanan::where('transaksi_id', $transaksi->id)
          ->whereIn('layanan_id', $layananToRemove)
          ->delete();
      }

      // Add new layanan
      foreach ($layananToAdd as $layananId) {
        DetailLayanan::create([
          'transaksi_id' => $transaksi->id,
          'layanan_id' => $layananId,
        ]);
      }

      // Retrieve the updated transaction
      $updatedTransaction = Transaksi::find($transaksi->id);
      Log::info('Transaction Updated: ', ['transaction' => $updatedTransaction->toArray()]);

      $existingDetailLayanan = $transaksi->detail_layanan;

      if ($transaksi->pelanggan->member_id) {
        $previousTotalPoint = PointLog::where('member_id', $transaksi->pelanggan->member_id)->where('transaksi_id', $transaksi->id)->sum('point');
        $updatedTotalPoint = 0;

        foreach ($validatedDataLayanan['layanan_id'] as $index => $layananId) {
          if ($layananId) {
            if ($transaksi->pelanggan->member_id) {
              $layananPoint = Layanan::where('id', $layananId)->first()->point;
              $updatedTotalPoint += $layananPoint;
            }
          }
        }

        Log::info('Updated Total Point: ', ['point' => $updatedTotalPoint]);

        if ($previousTotalPoint !== $updatedTotalPoint) {
          // updating the member point
          $member = Member::where('id', $transaksi->pelanggan->member_id)->first();
          Log::info('Before Member Point Updated: ', ['member' => $member]);

          $member->update([
            'experience_point' => $member->experience_point - $previousTotalPoint + $updatedTotalPoint,
            'redeemable_point' => $member->redeemable_point - $previousTotalPoint + $updatedTotalPoint,
          ]);

          // updating the point log
          $pointLog = PointLog::where('member_id', $transaksi->pelanggan->member_id)->where('transaksi_id', $transaksi->id)->first();
          $pointLog->update([
            'point' => $updatedTotalPoint,
          ]);

          Log::info('Member Point Updated: ', ['member' => $member]);
        }
      }

      return redirect('/dashboard/transaksi')->with('success', 'Data transaksi telah diupdate!!');
    } catch (\Exception $e) {
      Log::error('Transaction Update Error: ', ['message' => $e->getMessage()]);
      return redirect('/dashboard/transaksi')->with('error', 'Terjadi kesalahan saat mengupdate transaksi: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Transaksi $transaksi)
  {
    try {
      $deletedDetailLayanan = DetailLayanan::where('transaksi_id', $transaksi->id)->delete();
      Log::info('Detail Layanan Deleted: ', ['Detail Layanan' => $deletedDetailLayanan]);
      $deletedTransaction = Transaksi::destroy($transaksi->id);
      Log::info('Transaction Deleted: ', ['transaction' => $deletedTransaction]);

      return redirect('/dashboard/transaksi')->with('success', 'Transaksi telah terhapus!!!');
    } catch (\Exception $e) {
      Log::error('Transaction Creation Error: ', ['message' => $e->getMessage()]);

      return redirect('/dashboard/transaksi')->with('error', 'Terjadi kesalahan saat menghapus transaksi');
    }
  }

  public function thisWeek()
  {
    try {
      // Menentukan awal dan akhir minggu ini
      $startOfWeek = Carbon::now()->startOfWeek();
      $endOfWeek = Carbon::now()->endOfWeek();

      // Mengambil data transaksi dalam rentang tanggal minggu ini
      $transaksis = Transaksi::whereBetween('date', [$startOfWeek, $endOfWeek])
        ->orderBy('date', 'desc')
        ->get();

      return view('dashboard.transaksi.index', [
        'transaksis' => $transaksis,
      ]);
    } catch (\Exception $e) {
      Log::error('This Week Data Error: ', ['message' => $e->getMessage()]);

      return redirect('/dashboard/transaksi')->with('error', 'Terjadi kesalahan saat menghapus transaksi');
    }
  }

  public function thisMonth()
  {
    try {
      // Menentukan awal dan akhir minggu ini
      $startOfMonth = Carbon::now()->startOfMonth();
      $endOfMonth = Carbon::now()->endOfMonth();

      // Mengambil data transaksi dalam rentang tanggal minggu ini
      $transaksis = Transaksi::whereBetween('date', [$startOfMonth, $endOfMonth])
        ->orderBy('date', 'desc')
        ->get();

      return view('dashboard.transaksi.index', [
        'transaksis' => $transaksis,
      ]);
    } catch (\Exception $e) {
      Log::error('This Month Data Error: ', ['message' => $e->getMessage()]);

      return redirect('/dashboard/transaksi')->with('error', 'Terjadi kesalahan saat mengambil data per bulan');
    }
  }

  public function thisYear()
  {
    try {
      // Menentukan awal dan akhir minggu ini
      $startOfYear = Carbon::now()->startOfYear();
      $endOfYear = Carbon::now()->endOfYear();

      // Mengambil data transaksi dalam rentang tanggal minggu ini
      $transaksis = Transaksi::whereBetween('date', [$startOfYear, $endOfYear])
        ->orderBy('date', 'desc')
        ->get();

      return view('dashboard.transaksi.index', [
        'transaksis' => $transaksis,
      ]);
    } catch (\Exception $e) {
      Log::error('This Year Data Error: ', ['message' => $e->getMessage()]);

      return redirect('/dashboard/transaksi')->with('error', 'Terjadi kesalahan saat mengambil data per tahun');
    }
  }

  public function searchFromDate(Request $request)
  {
    $validatedDate = $request->validate([
      'min_date' => 'date',
      'max_date' => 'date'
    ]);

    $min = $validatedDate['min_date'];
    $max = $validatedDate['max_date'];

    $filteredData = Transaksi::whereBetween('date', [$min, $max])->orderBy('date', 'desc')->get();

    return view('dashboard.transaksi.index', [
      'transaksis' => $filteredData,
    ]);
  }

  // activeSwitch for edit transaksi when changing between displaying all layanan or only active layanan
  // public function activeSwitch(Request $request)
  // {
  //   try {
  //     $isActive = $request->input('isActive');

  //     if ($isActive) {
  //       Log::info('Active Switch: ', ['message' => 'All Layanan']);
  //       $layanans = Layanan::all();
  //     } else {
  //       Log::info('Active Switch: ', ['message' => 'Active Layanan']);
  //       $layanans = Layanan::where('is_active', true)->get();
  //     }
  //     Log::info('Layanan', ['layanan' => $layanans]);

  //     $data = [];

  //     foreach ($layanans as $layanan) {
  //       $data[] = [
  //         'id' => $layanan->id,
  //         'nama_layanan' => $layanan->nama_layanan,
  //         'harga' => $layanan->harga,
  //       ];
  //     }
  //     Log::info('Data Layanan', ['data' => $data]);

  //     if ($request->expectsJson()) {
  //       if ($layanans->isEmpty()) {
  //         return response()->json([]);
  //       } else {
  //         return response()->json($data, 200);
  //       }
  //     }

  //     return $data;
  //   } catch (\Exception $e) {
  //     Log::error('Active Switch Error:', ['message' => $e->getMessage()]);
  //     return back()->with('error', 'Terjadi kesalahan saat mengubah status layanan');
  //   }
  // }
}
