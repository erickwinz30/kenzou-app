<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PointLog;
use App\Models\Pelanggan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
        'tanggal_lahir' => 'required',
        'password' => 'required|min:5|max:255',
      ]);

      $validatedPhoneNumber = $request->validate([
        'nomor_telepon' => 'required|min:10|max:15|unique:members',
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
          $newMemberId = Member::where('email', $newMember->email)->first();
          $findPhoneNumber = Pelanggan::where('nomor_telepon', $validatedPhoneNumber['nomor_telepon'])->first();

          Member::where('email', $newMember->email)->update(['nomor_telepon' => $validatedPhoneNumber['nomor_telepon'], 'experience_point' => 25, 'redeemable_point' => 25]);

          $pointLogNewMember = PointLog::create([
            'member_id' => $newMemberId->id,
            'point' => 25,
            'status' => 'Pendaftaran Member Baru dengan Referral',
            'date' => Carbon::now('Asia/Jakarta'),
          ]);

          $pointLogMemberReferred = PointLog::create([
            'member_id' => $findMember->id,
            'point' => 25,
            'status' => 'Referral dari Member Baru',
            'point_from' => $newMemberId->id,
            'date' => Carbon::now('Asia/Jakarta'),
          ]);

          Log::info('Data member baru: ', ['member' => $validatedData]);
          Log::info('Check id Member baru: ', ['member' => $newMember]);
          Log::info('Point Log New Member: ', ['New Member' => $pointLogNewMember]);
          Log::info('Point Log Member Referred: ', ['Referred Member' => $pointLogMemberReferred]);

          if ($findPhoneNumber) {
            Pelanggan::where('nomor_telepon', $validatedPhoneNumber['nomor_telepon'])->update(['member_id' => $newMemberId->id]);
          } else {
            Pelanggan::create(['member_id' => $newMemberId->id, 'nomor_telepon' => $validatedPhoneNumber['nomor_telepon']]);
          }

          return redirect()->route('login')->with('success', 'Akun telah terdaftar!!!');
        } else {
          return back()->with('error', 'Kode referral tidak ditemukan!!!');
        }
      } else {
        $validatedData['experience_point'] = 10;
        $validatedData['redeemable_point'] = 10;
        $newMember = Member::create($validatedData);
        $newMemberId = Member::where('email', $newMember->email)->first();

        $pointLogNewMember = PointLog::create([
          'member_id' => $newMemberId->id,
          'point' => 10,
          'status' => 'Pendaftaran Member Baru',
          'date' => Carbon::now('Asia/Jakarta'),
        ]);

        Log::info('Data member baru: ', ['member' => $validatedData]);
        Log::info('Check id Member baru: ', ['member' => $newMember]);
        Log::info('Point Log New Member: ', ['New Member' => $pointLogNewMember]);

        $findPhoneNumber = Pelanggan::where('nomor_telepon', $validatedPhoneNumber['nomor_telepon'])->first();

        if ($findPhoneNumber) {
          Pelanggan::where('nomor_telepon', $validatedPhoneNumber['nomor_telepon'])->update(['member_id' => $newMemberId->id]);
        } else {
          Pelanggan::create(['member_id' => $newMemberId->id, 'nomor_telepon' => $validatedPhoneNumber['nomor_telepon']]);
        }

        return redirect()->route('login')->with('success', 'Akun telah terdaftar!!!');
      }
    } catch (\Exception $e) {
      Log::error('Register Member Error: ', ['message' => $e->getMessage()]);
      Log::error('Register Member Error Trace: ', ['message' => $e->getTraceAsString()]);

      return redirect('/register')->with('error', $e->getMessage());
    }
  }
}
