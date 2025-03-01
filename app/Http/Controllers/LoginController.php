<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function index()
  {
    return view('dashboard.login.login');
  }

  public function adminRegisterIndex()
  {
    return view('dashboard.login.admin-register');
  }

  public function authenticate(Request $request)
  {
    $credentials = $request->validate([
      'username' => 'required|min:3|max:255',
      'password' => 'required|min:5|max:255',
    ]);

    // dd($credentials);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      if (Auth::user()->is_admin == 0) {
        return redirect()->route('transactionPage');
      } else if (Auth::user()->is_admin == 1) {
        return redirect()->route('dashboard');
      }
    }

    return back()->with('error', 'Login failed!');
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('dashboard-login');
  }
}
