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
            $findMember = Member::where('email', $member->email)->first();

            if ($findMember) {
                Auth::guard('member')->login($findMember);

                session()->regenerate();

                Log::info('Login Successful: ', ['member' => $findMember]);

                return redirect()->route('homepage');
            } else {
                $newMember = Member::create([
                    'nama' => $member->name,
                    'email' => $member->email,
                    'google_id' => $member->id,
                    'password' => Hash::make(Str::random(16)),
                ]);

                Auth::guard('member')->login($newMember);

                session()->regenerate();

                Log::info('Google Register Successful: ', ['member' => $newMember]);

                return redirect()->route('homepage');
            }
        } catch (\Exception $e) {
            Log::error('Error during Google sign-in: ', ['message' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Gagal sign in dengan Google.');
        }
        
    }
}
