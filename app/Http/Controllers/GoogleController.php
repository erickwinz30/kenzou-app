<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Redirect;

class GoogleController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $member = Socialite::driver('google')->user();

            Log::info('$member: ', ['member' => $member]);

            $findMember = Member::where('email', $member->email)->first();

            Log::info('Check if member is existing: ', ['findMember' => $findMember]);

            if ($findMember) {
                Auth::guard('member')->login($findMember);

                session()->regenerate();

                Log::info('Login Successful: ', ['member' => $findMember]);

                if($findMember->nomor_telepon) {
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
                    return redirect()->with('error', 'Gagal sign in karena auth gagal dengan Google.');
                }

            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorTrace = $e->getTraceAsString();
            Log::error('Error during Google sign-in: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
            return redirect()->route('login')->with('error', 'Gagal sign in dengan Google.');
        }

    }

    public function viewAfterGoogleCallback() {
        return view('member.login.next-google-registration');
    }

    public function nextRegisterStore(Request $request) {
        // dd($request);
        if(Auth::guard('member')->user()->nomor_telepon) {
            return redirect()->route('homepage');
        } else {
            try{
                $validatedData = $request->validate([
                    'nomor_telepon' => 'required|unique:members',
                    'tanggal_lahir' => 'required',
                    // 'referral_code' => 'max:8',
                ]);

                $currentLoginUser = Auth::guard('member')->user()->id;

                Member::where('id', $currentLoginUser)->update($validatedData);

                return redirect()->route('homepage');
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                $errorTrace = $e->getTraceAsString();
                Log::error('Error during Google sign-in: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
                return redirect()->route('register-next')->with('error', $errorMessage);
            }
        }

    }
}
