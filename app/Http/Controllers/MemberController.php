<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Voucher;
use App\Models\PointLog;
use App\Models\Transaksi;
use App\Models\OwnedVoucher;
use Illuminate\Http\Request;
use App\Models\BadgeLeaderboard;
use App\Models\ChallengeProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
  public function index()
  {
    $listChallengeFinish = ChallengeProgress::where('member_id', Auth::guard('member')->user()->id)->where('is_completed', true)
      ->where('is_used', false)->whereHas('challenge', function ($query) {
        $query->where('is_active', true);
      })
      ->get();

    $listChallengeNotFinish = ChallengeProgress::where('member_id', Auth::guard('member')->user()->id)->where('is_completed', false)
      ->whereHas('challenge', function ($query) {
        $query->where('is_active', true);
      })
      ->get();

    return view('member.index', [
      'finishChallengeProgress' => $listChallengeFinish,
      'unfinishChallengeProgress' => $listChallengeNotFinish,
    ]);
  }

  public function account()
  {
    $member = Auth::guard('member')->user();
    $badge = DB::table('badges')
      ->where('min_point', '<=', $member->experience_point)
      ->where('max_point', '>=', $member->experience_point)
      ->first();

    return view('member.more.index', [
      'information' => $member,
      'badge' => $badge,
    ]);
  }

  public function viewAccountEdit()
  {
    if (!Auth::guard('member')->user()->id) {
      return view('error-404');
    }
    return view('member.more.edit-profile', [
      'member' => Auth::guard('member')->user(),
    ]);
  }

  public function accountUpdate(Request $request)
  {
    try {
      if (!Auth::guard('member')->user()->id) {
        return view('error-404-dashboard');
      }

      $member = Auth::guard('member')->user();

      if ($request->nama !== $member->nama) {
        $rules['nama'] = 'required|min:3|max:255';
      }

      if (!$member->google_id) {
        if ($request->email !== $member->email) {
          $rules['email'] = 'required|min:5|max:255|unique:members|email:dns';
        }
      }

      if ($request->nomor_telepon !== $member->nomor_telepon) {
        $rules['nomor_telepon'] = 'required|min:10|max:15|unique:members';
      }

      if (date('Y-m-d', strtotime($request->tanggal_lahir)) !== date('Y-m-d', strtotime($member->tanggal_lahir))) {
        $rules['tanggal_lahir'] = 'required|date';
      }

      if ($request->password !== null) {
        $rules['password'] = 'required|min:5|max:255';
      }

      $validatedData = $request->validate($rules);

      if ($request->password !== null) {
        $validatedData['password'] = Hash::make($validatedData['password']);
      }

      Member::where('id', $member->id)->update($validatedData);

      return redirect()->back()->with('success', 'Data profil berhasil diupdate!');
    } catch (\Exception $e) {
      Log::error('Error when updating account data: ', ['error' => $e->getMessage()]);
      Log::error('Error when updating account data: ', ['error' => $e->getTraceAsString()]);
      return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data akun!' + $e->getMessage());
    }
  }

  public function transactionHistory()
  {
    $member = Auth::guard('member')->user();

    return view('member.more.transactionHistory', [
      'transactions' => $member->pelanggan->transaksi()->orderBy('created_at', 'desc')->get(),
    ]);
  }

  public function viewTransactionHistory(Transaksi $transaksi)
  {
    return view('member.more.viewTransactionHistory', [
      'transaction' => $transaksi,
    ]);
  }

  public function pointHistory()
  {
    $member = Auth::guard('member')->user();

    return view('member.more.pointHistory', [
      'informations' => PointLog::where('member_id', $member->id)->orderBy('created_at', 'desc')->get(),
    ]);
  }

  public function viewOwnedVoucher()
  {
    // $member = Auth::guard('member')->user();
    $vouchers = Voucher::where('is_active', true)->get();
    $ownedVouchers = OwnedVoucher::where('member_id', Auth::guard('member')->user()->id)->where('is_used', false)
      ->whereHas('voucher', function ($query) {
        $query->where('is_active', true);
      })->get();

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

  public function viewDetailVoucher(Voucher $voucher)
  {
    $member = Auth::guard('member')->user();
    $ownedVouchers = $member->ownedVouchers;

    return view('member.voucher.view', compact('voucher', 'ownedVouchers'));
  }

  public function viewChallengeProgress(ChallengeProgress $challengeProgress)
  {
    // echo $challengeProgress;
    return view('member.challenge.view', [
      'progress' => $challengeProgress,
    ]);
  }

  public function viewLeaderboard()
  {
    $members = Member::orderBy('experience_point', 'desc')->limit(10)->get();
    $rankFirst = BadgeLeaderboard::where('rank', 1)->first()->image;
    $rankSecond = BadgeLeaderboard::where('rank', 2)->first()->image;
    $rankThird = BadgeLeaderboard::where('rank', 3)->first()->image;

    $loggedInMember = Auth::guard('member')->user();
    $loggedInMemberExperience = $loggedInMember->experience_point;

    return view('member.leaderboard.index', [
      'members' => $members,
      'rankFirst' => $rankFirst,
      'rankSecond' => $rankSecond,
      'rankThird' => $rankThird,
    ]);
  }
}
