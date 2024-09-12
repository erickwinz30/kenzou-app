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
    public function index() {
        return view('member.login.register');
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'nama' => "required|min:3|max:255",
                'email' => "required|min:5|max:255|unique:members|email:dns",
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

            $newMember = Member::create($validatedData);

            Log::info('Data member baru: ' , ['member' => $validatedData]);
            Log::info('Check id Member baru: ' , ['member' => $newMember]);

            return redirect()->route('login')->with('success', 'Akun telah terdaftar!!!');

        } catch(\Exception $e) {
            Log::error('Register Member Error: ', ['message' => $e->getMessage()]);

            return redirect('/registrasi')->with('error', 'Terjadi kesalahan saat menambahkan akun baru');
        }
    }
}
