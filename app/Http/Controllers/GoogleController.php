<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\PointLog;
use App\Models\Pelanggan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    try {
      $member = Socialite::driver('google')->user();

      Log::info('$member: ', ['member' => $member]);

      $findMember = Member::where('email', $member->email)->first();

      Log::info('Check if member is existing: ', ['findMember' => $findMember]);

      if ($findMember) {
        Auth::guard('member')->login($findMember);

        session()->regenerate();

        Log::info('Login Successful: ', ['member' => $findMember]);

        if ($findMember->nomor_telepon) {
          return redirect()->route('homepage');
        } else {
          return redirect()->route('register-next');
        }
      } else {
        do {
          $referralCode = strtoupper(Str::random(8));

          // Optionally, filter out lowercase letters (if they appear)
          $referralCode = preg_replace('/[^A-Z0-9]/', '', $referralCode);
        } while (Member::where('referral_code', $referralCode)->exists());

        $newMember = Member::create([
          'nama' => $member->getName(),
          'email' => $member->email,
          'google_id' => $member->getId(),
          'password' => Hash::make(Str::random(16)),
          'referral_code' => $referralCode,
        ]);

        Log::info('Google Register Successful: ', ['member' => $newMember]);

        $memberCheck = Member::where('email', $newMember->email)->first();

        Auth::guard('member')->login($memberCheck);

        session()->regenerate();

        if (Auth::guard('member')->check()) {
          // Pengguna sudah login
          return redirect()->route('register-next');
        } else {
          // Pengguna belum login
          return redirect()->with('error', 'Terjadi kesalahan saat sign-in menggunakan Google');
        }
      }
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error('Error during Google sign-in: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return redirect()->route('login')->with('error', 'Gagal sign in dengan Google.');
    }
  }

  public function viewAfterGoogleCallback()
  {
    if (Auth::guard('member')->user()->nomor_telepon) {
      return redirect()->route('homepage');
    } else {
      return view('member.login.next-google-registration');
    }
  }

  public function nextRegisterStore(Request $request)
  {
    $member = Auth::guard('member')->user()->nomor_telepon;

    if ($member) {
      return redirect()->route('homepage');
    } else {
      try {
        $validatedData = $request->validate([
          'nomor_telepon' => 'required|unique:members',
          'tanggal_lahir' => 'required',
        ]);

        if ($request->referral_code) {
          $findMember = Member::where('referral_code', $request->referral_code)->first();
          $findCurrentMember = Auth::guard('member')->id();

          if ($findMember) {
            $memberCurrentExperiencePoint = $findMember->experience_point;
            $memberCurrentRedeemablePoint = $findMember->redeemable_point;

            $afterRegisterExperiencePoint = $memberCurrentExperiencePoint + 25;
            $afterRegisterRedeemablePoint = $memberCurrentRedeemablePoint + 25;
            Log::info('Perolehan Point Member: ', ['member' => $afterRegisterExperiencePoint]);

            $findMember->update(['experience_point' => $afterRegisterExperiencePoint, 'redeemable_point' => $afterRegisterRedeemablePoint]);
            $findPhoneNumber = Pelanggan::where('nomor_telepon', $validatedData['nomor_telepon'])->first();

            if ($findPhoneNumber) {
              Pelanggan::where('nomor_telepon', $validatedData['nomor_telepon'])->update(['member_id' => $findCurrentMember]);
              Member::where('id', $findCurrentMember)->update(['nomor_telepon' => $validatedData['nomor_telepon'], 'tanggal_lahir' => $validatedData['tanggal_lahir'], 'experience_point' => 25, 'redeemable_point' => 25]);

              $pointLogNewMember = PointLog::create([
                'member_id' => $findCurrentMember,
                'point' => 25,
                'status' => 'Pendaftaran Member Baru dengan Referral',
                'date' => Carbon::now('Asia/Jakarta'),
              ]);

              Log::info('Point Log New Member: ', ['New Member' => $pointLogNewMember]);
            } else {
              Pelanggan::create(['member_id' => $findCurrentMember, 'nomor_telepon' => $validatedData['nomor_telepon']]);
              Member::where('id', $findCurrentMember)->update(['nomor_telepon' => $validatedData['nomor_telepon'], 'tanggal_lahir' => $validatedData['tanggal_lahir'], 'experience_point' => 25, 'redeemable_point' => 25]);

              $pointLogNewMember = PointLog::create([
                'member_id' => $findCurrentMember,
                'point' => 25,
                'status' => 'Pendaftaran Member Baru dengan Referral',
                'date' => Carbon::now('Asia/Jakarta'),
              ]);

              $pointLogMemberReferred = PointLog::create([
                'member_id' => $findMember->id,
                'point' => 25,
                'status' => 'Referral dari Member Baru',
                'point_from' => $findCurrentMember,
                'date' => Carbon::now('Asia/Jakarta'),
              ]);

              Log::info('Point Log New Member: ', ['New Member' => $pointLogNewMember]);
              Log::info('Point Log Member Referred: ', ['Referred Member' => $pointLogMemberReferred]);
            }

            return redirect()->route('homepage');
          } else {
            return back()->with('error', 'Kode referral tidak ditemukan!!!');
          }
        } else {
          $currentLoginUser = Auth::guard('member')->id();
          Member::where('id', $currentLoginUser)->update(['tanggal_lahir' => $validatedData['tanggal_lahir']]);

          $findPhoneNumber = Pelanggan::where('nomor_telepon', $validatedData['nomor_telepon'])->first();

          if ($findPhoneNumber) {
            Pelanggan::where('nomor_telepon', $validatedData['nomor_telepon'])->update(['member_id' => $currentLoginUser]);
            Member::where('id', $currentLoginUser)->update(['nomor_telepon' => $validatedData['nomor_telepon'], 'experience_point' => 10, 'redeemable_point' => 10]);

            $pointLogNewMember = PointLog::create([
              'member_id' => $currentLoginUser,
              'point' => 10,
              'status' => 'Pendaftaran Member Baru',
              'date' => Carbon::now('Asia/Jakarta'),
            ]);
          } else {
            Pelanggan::create(['member_id' => $currentLoginUser, 'nomor_telepon' => $validatedData['nomor_telepon']]);
            Member::where('id', $currentLoginUser)->update(['nomor_telepon' => $validatedData['nomor_telepon'], 'experience_point' => 10, 'redeemable_point' => 10]);

            $pointLogNewMember = PointLog::create([
              'member_id' => $currentLoginUser,
              'point' => 10,
              'status' => 'Pendaftaran Member Baru',
              'date' => Carbon::now('Asia/Jakarta'),
            ]);
          }

          return redirect()->route('homepage');
        }
      } catch (\Exception $e) {
        $errorMessage = $e->getMessage();
        $errorTrace = $e->getTraceAsString();
        Log::error('Error during Google sign-in: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
        return redirect()->route('register-next')->with('error', $errorMessage);
      }
    }
  }
}
