<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PointLog;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
  public function index()
  {
    return view('member.index');
  }

  public function account()
  {
    return view('member.more.index', [
      'information' => Auth::guard('member')->user(),
    ]);
  }

  public function transactionHistory()
  {
    $member = Auth::guard('member')->user();

    return view('member.more.transactionHistory', [
      // 'informations' => $member->transactions()->orderBy('created_at', 'desc')->get(),
    ]);
  }

  public function pointHistory()
  {
    $member = Auth::guard('member')->user();

    return view('member.more.pointHistory', [
      'informations' => PointLog::where('member_id', $member->id)->orderBy('created_at', 'desc')->get(),
    ]);
  }
}
