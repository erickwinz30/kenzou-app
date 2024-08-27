<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MemberLoginController extends Controller
{
    public function index() {
        return view('member.login.login');
    }

    public function authenticate(Request $request) {
        try {
            $credentials = $request->validate([
                'email' => 'required|min:5|max:255',
                'password' => 'required',
            ]);

            Log::info('message', ['Login data' => $credentials]);
    
            if(Auth::guard('member')->attempt($credentials)) {
                try {
                    Log::info('message', ['Credentials data:' => $credentials]);
                    $request->session()->regenerate();
        
                    return redirect()->route('homepage');

                    Log::info('message', "Log in success");
                } catch(\Exception $e) {
                    Log::error('Log in Error: ', ['message' => $e->getMessage()]);

                    return back()->with('error', 'Maaf, akun yang dimasukkan salah!!!');
                }
                
            } else {
                return back()->with('error', 'Login failed!');
            }

        } catch(\Exception $e) {
            Log::error('Log in Error: ', ['message' => $e->getMessage()]);

            return redirect('/login')->with('error', 'Terjadi kesalahan saat melakukan log in');
        }
    }

    public function logout(Request $request) {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
