<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryLayanan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CategoryLayananController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('dashboard.layanan.category.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'required|min:3|max:255',
    ]);
    CategoryLayanan::create($validatedData);
    return redirect('/dashboard/layanan')->with('success', 'Kategori layanan baru telah tertambah!');
  }

  /**
   * Display the specified resource.
   */
  public function show(CategoryLayanan $categoryLayanan)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(CategoryLayanan $categoryLayanan)
  {
    return view('dashboard.layanan.category.edit', [
      'category' => $categoryLayanan,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, CategoryLayanan $categoryLayanan)
  {
    $validatedData = $request->validate([
      'name' => 'required|min:3|max:255',
    ]);
    $categoryLayanan->update($validatedData);
    return redirect('/dashboard/layanan')->with('success', 'Kategori layanan telah diperbarui!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(CategoryLayanan $categoryLayanan)
  {
    $categoryLayanan->delete();
    return redirect('/dashboard/layanan')->with('success', 'Kategori layanan telah dihapus!');
  }

  public function toggleActivation(Request $request)
  {
    try {
      $categoryLayanan = CategoryLayanan::where('id', $request->layananId)->first();
      $categoryLayanan->is_active = !$categoryLayanan->is_active;
      $categoryLayanan->save();

      return redirect('/dashboard/layanan')->with('success', 'Status aktivasi kategori layanan berhasil diubah!!!');
    } catch (\Exception $e) {
      Log::error("Error in CategoryLayananController@toggleActivation", ['error' => $e->getMessage()]);
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }
}
