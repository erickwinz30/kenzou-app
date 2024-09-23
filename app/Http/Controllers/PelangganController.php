<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PelangganController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('dashboard.pelanggan.index', [
      'pelanggans' => Pelanggan::all(),
    ]);
    // dd(Pelanggan::all());
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
    //
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
}
