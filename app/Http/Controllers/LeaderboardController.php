<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaderboardController extends Controller
{
  public function index()
  {
    $leaderboards = Member::orderBy('experience_point', 'desc')->get();
    return view('dashboard.leaderboard.index', [
      'leaderboards' => $leaderboards,
    ]);
  }
}
