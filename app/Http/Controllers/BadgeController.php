<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BadgeController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('dashboard.badge.index', [
      'badges' => Badge::all(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('dashboard.badge.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'nama' => 'required|max:255',
        'min_point' => 'required',
        'max_point' => 'required',
        'discount' => 'required',
        'image' => 'required|file|image|max:2048',
      ]);

      $validatedData['discount'] = $validatedData['discount'] / 100;
      $validatedData['image'] = $request->file('image')->store('badge-image');

      Badge::create($validatedData);

      return redirect('/dashboard/badge')->with('success', 'Badge berhasil ditambahkan');
    } catch (\Exception $e) {
      Log::error('Error saat penambahan data: ' . $e->getMessage());
      Log::error('Error saat penambahan data: ' . $e->getTraceAsString());
      return redirect('/dashboard/badge')->with('error', 'Badge gagal ditambahkan');
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Badge $badge)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Badge $badge)
  {
    return view('dashboard.badge.edit', [
      'badge' => $badge,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Badge $badge)
  {
    $rules = [
      'nama' => 'required|max:255',
      'min_point' => 'required',
      'max_point' => 'required',
      'discount' => 'required',
      'image' => 'file|image|max:2048',
    ];

    $validatedData = $request->validate($rules);

    if ($request->file('image')) {
      if ($badge->image) {
        Storage::delete($badge->image);
      }
      $validatedData['image'] = $request->file('image')->store('badge-image');
    }

    if ($validatedData['discount']) {
      $validatedData['discount'] = $validatedData['discount'] / 100;
    }

    Badge::where('id', $badge->id)->update($validatedData);

    return redirect('/dashboard/badge')->with('success', 'Badge berhasil diubah!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Badge $badge)
  {
    if ($badge->image) {
      Storage::delete($badge->image);
    }
    Badge::destroy($badge->id);

    return redirect('/dashboard/badge')->with('success', 'Badge telah berhasil dihapus!');
  }
}
