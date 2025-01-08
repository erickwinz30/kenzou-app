<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\BadgeLeaderboard;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BadgeLeaderboardController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $leaderboards = Member::orderBy('experience_point', 'desc')->get();

    $rankFirst = BadgeLeaderboard::where('rank', 1)->first()->image;
    $rankSecond = BadgeLeaderboard::where('rank', 2)->first()->image;
    $rankThird = BadgeLeaderboard::where('rank', 3)->first()->image;

    return view('dashboard.leaderboard.index', [
      'leaderboards' => $leaderboards,
      'rankFirst' => $rankFirst,
      'rankSecond' => $rankSecond,
      'rankThird' => $rankThird,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $existingRanks = BadgeLeaderboard::pluck('rank')->toArray();
    return view('dashboard.badge.leaderboard.create', compact('existingRanks'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // dd($request->all());
    try {
      $validatedData = $request->validate([
        'badge_name' => 'required|max:255',
        'rank' => 'required|numeric',
        'discount' => 'required|numeric',
      ]);

      $validatedData['discount'] = $validatedData['discount'] / 100;
      $validatedData['image'] = $request->file('image')->store('badge-leaderboard-image');

      BadgeLeaderboard::create($validatedData);

      return redirect('/dashboard/badge')->with('success', 'Leaderboard berhasil ditambahkan');
    } catch (\Exception $e) {
      Log::error('Error saat penambahan data: ' . $e->getMessage());
      Log::error('Error saat penambahan data: ' . $e->getTraceAsString());
      return redirect('/dashboard/leaderboard')->with('error', 'Leaderboard gagal ditambahkan');
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(BadgeLeaderboard $badgeLeaderboard)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(BadgeLeaderboard $badgeLeaderboard)
  {
    return view('dashboard.badge.leaderboard.edit', [
      'leaderboard' => $badgeLeaderboard,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, BadgeLeaderboard $badgeLeaderboard)
  {
    try {
      $rules = [];

      if ($request->badge_name !== $badgeLeaderboard->badge_name) {
        $rules['badge_name'] = 'required|max:255';
      }

      if ($request->rank != $badgeLeaderboard->rank) {
        $rules['rank'] = 'required|numeric';
      }

      if (round(floatval($request->discount), 2) !== round($badgeLeaderboard->discount * 100, 2)) {
        $rules['discount'] = 'required|numeric|between:0,99.99';
      }

      if (empty($rules) && !$request->hasFile('image')) {
        return redirect()->back()->with('error', 'Tidak ada data yang diupdate!!');
      }

      $validatedData = $request->validate($rules);

      if ($request->hasFile('image')) {
        if ($badgeLeaderboard->image) {
          Storage::delete($badgeLeaderboard->image);
        }
        $validatedData['image'] = $request->file('image')->store('badge-image');
      }

      if (isset($validatedData['discount'])) {
        $validatedData['discount'] = $validatedData['discount'] / 100;
      }

      $updated = $badgeLeaderboard->update($validatedData);
      Log::info('Data Badge Leaderboard berhasil diupdate: ' . $updated);

      return redirect('/dashboard/badge')->with('success', 'Badge untuk Leaderboard berhasil diupdate');
    } catch (\Exception $e) {
      Log::error('Error saat update data: ' . $e->getMessage());
      Log::error('Error saat update data: ' . $e->getTraceAsString());
      return redirect('/dashboard/badge')->with('error', 'Leaderboard gagal diupdate');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(BadgeLeaderboard $badgeLeaderboard)
  {
    if ($badgeLeaderboard->image) {
      Storage::delete($badgeLeaderboard->image);
    }
    BadgeLeaderboard::destroy($badgeLeaderboard->id);

    return redirect('/dashboard/badge')->with('success', 'Badge Leaderboard telah berhasil dihapus!');
  }

  public function leaderboardActiveSwitch(Request $request)
  {
    try {
      $badge = badgeLeaderboard::find($request->leaderboardId);
      $badge->is_active = !$badge->is_active;
      $badge->save();

      return redirect('/dashboard/badge')->with('success', 'Status leaderboard telah diubah!');
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      return redirect('/dashboard/badge')->with('error', 'Status leaderboard gagal diubah!');
    }
  }
}
