<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\BadgeLeaderboard;
use App\Http\Controllers\Controller;

class LeaderboardController extends Controller
{
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
}
