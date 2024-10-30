<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Voucher;
use App\Models\PointLog;
use App\Models\OwnedVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OwnedVoucherController extends Controller
{
  public function index()
  {
    $member = Auth::guard('member')->user();
    $vouchers = Voucher::where('is_active', true)->get();
    $ownedVouchers = $member->ownedVouchers;

    return view('member.voucher.index', compact('vouchers', 'ownedVouchers'));
  }

  public function claimVoucher(Request $request)
  {
    if (Auth::guard('member')->user()->redeemable_point < $request->point_needed) {
      return redirect('/voucher')->with('error', 'Point tidak mencukupi!!!');
    } else {
      $voucher = Voucher::find($request->voucher_id);

      $remainingPoint = Auth::guard('member')->user()->redeemable_point - $voucher->point_needed;
      $updatedRemainingPoint = Member::where('id', Auth::guard('member')->user()->id)->update(['redeemable_point' => $remainingPoint]);
      Log::info('Remaining Point: ', ['Remaining Point' => $updatedRemainingPoint]);

      $pointLog = PointLog::create([
        'member_id' => Auth::guard('member')->user()->id,
        'point' => $voucher->point_needed,
        'status' => 'Redeem Voucher',
        'date' => Carbon::now('Asia/Jakarta'),
      ]);

      Log::info('Point Log Redeem Voucher: ', ['Point Log' => $pointLog]);

      $claimedVoucher = OwnedVoucher::create([
        'member_id' => Auth::guard('member')->user()->id,
        'voucher_id' => $voucher->id,
      ]);
      Log::info('Voucher Claimed', ['Voucher' => $claimedVoucher]);

      return redirect('/voucher')->with('success', 'Voucher telah diredeem!!!');
    }
  }
}
