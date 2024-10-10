<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PointLog;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        return view('error-404');
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
}
