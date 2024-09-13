<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MemberRegisterController extends Controller
{
  public function index()
  {
    return view('member.login.register');
  }

  public function store(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'nama' => 'required|min:3|max:255',
        'email' => 'required|min:5|max:255|unique:members|email:dns',
        'nomor_telepon' => 'required|min:10|max:15|unique:members',
        'tanggal_lahir' => 'required',
        'password' => 'required|min:5|max:255',
      ]);

      $validatedData['password'] = Hash::make($validatedData['password']);

      do {
        $referralCode = strtoupper(Str::random(8));

        // Optionally, filter out lowercase letters (if they appear)
        $referralCode = preg_replace('/[^A-Z0-9]/', '', $referralCode);
      } while (Member::where('referral_code', $referralCode)->exists());

      $validatedData['referral_code'] = $referralCode;

      if ($request->referral_code) {
        $findMember = Member::where('referral_code', $request->referral_code)->first();
        if ($findMember) {
          $memberCurrentExperiencePoint = $findMember->experience_point;
          $memberCurrentRedeemablePoint = $findMember->redeemable_point;

          $afterRegisterExperiencePoint = $memberCurrentExperiencePoint + 25;
          $afterRegisterRedeemablePoint = $memberCurrentRedeemablePoint + 25;

          $findMember->update(['experience_point' => $afterRegisterExperiencePoint, 'redeemable_point' => $afterRegisterRedeemablePoint]);

          Log::info('Perolehan Point Member: ', ['member' => $afterRegisterExperiencePoint]);

          $newMember = Member::create($validatedData);

          Member::where('email', $newMember->email)->update(['experience_point' => 25, 'redeemable_point' => 25]);

          Log::info('Data member baru: ', ['member' => $validatedData]);
          Log::info('Check id Member baru: ', ['member' => $newMember]);

          return redirect()->route('login')->with('success', 'Akun telah terdaftar!!!');
        } else {
          return back()->with('error', 'Kode referral tidak ditemukan!!!');
        }
      } else {
        $newMember = Member::create($validatedData);

        Log::info('Data member baru: ', ['member' => $validatedData]);
        Log::info('Check id Member baru: ', ['member' => $newMember]);

        return redirect()->route('login')->with('success', 'Akun telah terdaftar!!!');
      }
    } catch (\Exception $e) {
      Log::error('Register Member Error: ', ['message' => $e->getMessage()]);

      return redirect('/registrasi')->with('error', 'Terjadi kesalahan saat menambahkan akun baru');
    }
  }
}
