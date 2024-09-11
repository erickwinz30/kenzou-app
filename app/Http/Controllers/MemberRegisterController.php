<?php

namespace App\Http\Controllers;

use App\Models\Member;
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

            $newMember = Member::create($validatedData);

            Log::info('Data member baru: ' , ['member' => $validatedData]);
            Log::info('Check id Member baru: ' , ['member' => $newMember]);

            return redirect()->route('login')->with('success', 'Akun telah terdaftar!!!');

        } catch(\Exception $e) {
            Log::error('Transaction Update Error: ', ['message' => $e->getMessage()]);

            return redirect('/registrasi')->with('error', 'Terjadi kesalahan saat menambahkan akun baru');
        }
    }
}
