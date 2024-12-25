<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Layanan;
use App\Models\LayananLog;
use Illuminate\Http\Request;
use App\Models\DetailLayanan;
use App\Models\ChallengePrize;
use App\Models\CategoryLayanan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LayananController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('dashboard.layanan.index', [
      'layanans' => Layanan::all(),
      'categories' => CategoryLayanan::all(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('dashboard.layanan.create', [
      'categories' => CategoryLayanan::all(),
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'nama_layanan' => 'required|min:3|max:255',
      'category_layanan_id' => 'required',
      'harga' => 'required',
      'point' => 'required',
      'detail' => 'required|max:255',
    ]);

    // dd($validatedData);

    Layanan::create($validatedData);
    return redirect('/dashboard/layanan')->with('success', 'Layanan baru telah tertambah!');
  }

  /**
   * Display the specified resource.
   */
  public function show(Layanan $layanan)
  {
    return view('dashboard.layanan.view', [
      'layanan' => $layanan,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Layanan $layanan)
  {
    return view('dashboard.layanan.edit', [
      'layanan' => $layanan,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Layanan $layanan)
  {
    $rules = [
      'nama_layanan' => 'required|min:3|max:255',
      'harga' => 'required|numeric',
      'point' => 'required|numeric',
      'detail' => 'required|max:255',
    ];

    $validatedData = $request->validate($rules);

    Layanan::where('id', $layanan->id)->update($validatedData);

    $validatedData['updated_date'] = Carbon::now('Asia/Jakarta');

    return redirect('/dashboard/layanan')->with('success', 'Layanan telah diupdate!!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Layanan $layanan) {}

  public function toggleActivation(Request $request)
  {
    try {
      $layanan = Layanan::where('id', $request->layananId)->first();
      $layanan->is_active = !$layanan->is_active;
      $layanan->save();

      return redirect('/dashboard/layanan')->with('success', 'Status aktivasi layanan berhasil diubah!!!');
    } catch (\Exception $e) {
      Log::error("Error in ChallengeController@toggleActivation", ['error' => $e->getMessage()]);
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }
}
