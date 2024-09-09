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

class GoogleController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $member = Socialite::driver('google')->user();

            // dd($member);

            Log::info('$member: ', ['member' => $member]);

            $findMember = Member::where('email', $member->email)->first();

            Log::info('Check if member is existing: ', ['findMember' => $findMember]);

            if ($findMember) {
                Auth::guard('member')->login($findMember);

                session()->regenerate();

                Log::info('Login Successful: ', ['member' => $findMember]);

                return redirect()->route('homepage');
            } else {
                $newMember = Member::create([
                    'nama' => $member->getName(),
                    'email' => $member->email,
                    'google_id' => $member->getId(),
                    'password' => Hash::make(Str::random(16)),
                ]);

                Log::info('Laravel Account Created: ', ['newMember' => $newMember]);

                Auth::guard('member')->login($newMember);

                session()->regenerate();

                Log::info('Google Register Successful: ', ['member' => $newMember->id]);

                // Log::info('Google Register Successful: ', ['member_id' => $currentLoginId]);

                return redirect()->route('homepage');
            }
        } catch (\Exception $e) {
            Log::error('Error during Google sign-in: ', ['message' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Gagal sign in dengan Google.');
        }
        
    }

    public function viewAfterGoogleCallback() {
        return view('member.login.next-google-registration');
    }

    private function nextRegisterForm(Request $request) {

    }
}
