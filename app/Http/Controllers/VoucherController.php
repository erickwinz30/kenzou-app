<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('dashboard.voucher.index', [
      'vouchers' => Voucher::where('is_active', true)->get(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('dashboard.voucher.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'nama' => 'required|min:3|max:255',
        'description' => 'required|max:255',
        'point_needed' => 'required|numeric',
        'discount' => 'required|numeric',
        'minimum_transaction' => 'required|numeric',
        'from_date' => 'required|date',
        'to_date' => 'required|date',
      ]);

      $validatedData['discount'] = $validatedData['discount'] / 100;

      Voucher::create($validatedData);

      return redirect('/dashboard/voucher')->with('success', 'Voucher berhasil ditambahkan!');
    } catch (\Exception $e) {
      Log::info('Error saat menambahkan voucher: ' . $e->getMessage());
      Log::info('Error saat menambahkan voucher: ' . $e->getTraceAsString());
      return redirect()->back()->with('error', 'Gagal menambahkan voucher');
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Voucher $voucher)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Voucher $voucher)
  {
    return view('dashboard.voucher.edit', [
      'voucher' => $voucher,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Voucher $voucher)
  {
    try {
      if ($request->nama !== $voucher->nama) {
        $rules['nama'] = 'required|min:3|max:255';
      }

      if ($request->description !== $voucher->description) {
        $rules['description'] = 'required|max:255';
      }

      if ($request->point_needed != $voucher->point_needed) {
        $rules['point_needed'] = 'required|numeric';
      }

      if (round(floatval($request->discount), 2) !== round($voucher->discount * 100, 2)) {
        $rules['discount'] = 'required';
      }

      $minimumTransaction = floatval(str_replace(['.', ','], ['', '.'], $request->minimum_transaction));
      if ($minimumTransaction != $voucher->minimum_transaction) {
        $rules['minimum_transaction'] = 'required|numeric';
      }

      if ($request->is_active != $voucher->is_active) {
        $rules['is_active'] = 'required|boolean';
      }

      if (date('Y-m-d\TH:i', strtotime($request->from_date)) !== date('Y-m-d\TH:i', strtotime($voucher->from_date))) {
        $rules['from_date'] = 'required|date';
      }

      if (date('Y-m-d\TH:i', strtotime($request->to_date)) !== date('Y-m-d\TH:i', strtotime($voucher->to_date))) {
        $rules['to_date'] = 'required|date';
      }

      $validatedData = $request->validate($rules);

      if (round(floatval($request->discount), 2) !== round($voucher->discount * 100, 2)) {
        $validatedData['discount'] = $validatedData['discount'] / 100;
      }

      // dd($validatedData);

      Voucher::where('id', $voucher->id)->update($validatedData);

      return redirect('/dashboard/voucher')->with('success', 'Voucher berhasil diubah!');
    } catch (\Exception $e) {
      Log::info('Error saat mengubah voucher: ' . $e->getMessage());
      Log::info('Error saat mengubah voucher: ' . $e->getTraceAsString());
      return redirect()->back()->with('error', 'Gagal mengubah voucher');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Voucher $voucher)
  {
    Voucher::destroy($voucher->id);

    return redirect('/dashboard/voucher')->with('success', 'Voucher berhasil dihapus!');
  }

  public function nonActiveFetch(Request $request)
  {
    $dataNonActive = [];
    $vouchers = Voucher::where('is_active', false)->get();

    foreach ($vouchers as $voucher) {
      $dataNonActive[] = [
        'id' => $voucher->id,
        'nama' => $voucher->nama,
        'description' => $voucher->description,
        'discount' => $voucher->discount,
        'point_needed' => $voucher->point_needed,
        'is_active' => $voucher->is_active,
        'from_date' => Carbon::parse($voucher->from_date)->format('d-m-Y H:i:s'),
        'to_date' => Carbon::parse($voucher->to_date)->format('d-m-Y H:i:s'),
      ];
    }

    if ($request->wantsJson()) {
      if ($vouchers->isEmpty()) {
        return response()->json([]);
      } else {
        return response()->json($dataNonActive, 200);
      }
    }

    return $dataNonActive;
  }

  public function allVoucherFetch(Request $request)
  {
    $dataAllVoucher = [];
    $vouchers = Voucher::all();

    foreach ($vouchers as $voucher) {
      $dataAllVoucher[] = [
        'id' => $voucher->id,
        'nama' => $voucher->nama,
        'description' => $voucher->description,
        'discount' => $voucher->discount,
        'point_needed' => $voucher->point_needed,
        'is_active' => $voucher->is_active,
        'from_date' => Carbon::parse($voucher->from_date)->format('d-m-Y H:i:s'),
        'to_date' => Carbon::parse($voucher->to_date)->format('d-m-Y H:i:s'),
      ];
    }

    if ($request->wantsJson()) {
      if ($vouchers->isEmpty()) {
        return response()->json([]);
      } else {
        return response()->json($dataAllVoucher, 200);
      }
    }

    return $dataAllVoucher;
  }

  public function toggleActivation(Request $request)
  {
    try {
      $voucher = Voucher::findOrFail($request->voucherId);
      $voucher->is_active = !$voucher->is_active;
      $voucher->save();

      return redirect('/dashboard/voucher')->with('success', 'Status voucher berhasil diubah!!!');
    } catch (\Exception $e) {
      Log::error("Error in ChallengeController@toggleActivation", ['error' => $e->getMessage()]);
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }
}
