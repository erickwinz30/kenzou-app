<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  public function index()
  {
    return view('dashboard.login.admin-register');
  }

  public function authenticate(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'nama' => 'required|min:3|max:255',
        'username' => 'required|min:3|max:255',
        'nomor_telepon' => 'required|min:10|max:15|unique:users',
        'password' => 'required|min:5|max:255',
      ]);

      $validatedData['password'] = Hash::make($validatedData['password']);
      $validatedData['is_admin'] = 1;

      User::create($validatedData);

      return redirect('/dashboard/login')->with('success', 'Register admin success!');
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      Log::error($e->getTraceAsString());
      return back()->with('error', $e->getMessage());
    }
  }
}
