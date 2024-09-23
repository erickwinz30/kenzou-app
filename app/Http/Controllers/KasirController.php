<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class KasirController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('dashboard.kasir.index', [
      'users' => User::all(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'nama' => 'required|min:5|max:255',
      'username' => 'required|unique:users|min:3|max:255',
      'email' => 'unique:users|email:dns',
      'nomor_telepon' => 'required|unique:users',
      'is_admin' => 'required|numeric',
      'password' => 'required|min:5|max:255',
    ]);

    $validatedData['password'] = Hash::make($validatedData['password']);

    User::create($validatedData);

    return redirect('/dashboard/kasir')->with('success', 'Data kasir telah ditambah!');
  }

  /**
   * Display the specified resource.
   */
  public function show(User $user)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(User $user)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $dataKasir = User::where('id', $request->id)->first();

    if ($request->nama !== $dataKasir->nama) {
      $rules['nama'] = 'required|min:5|max:255';
    };

    if ($request->username !== $dataKasir->username) {
      $rules['username'] = 'required|unique:users|min:3|max:255';
    };

    if ($request->email !== $dataKasir->email) {
      $rules['email'] = 'unique:users|email:dns';
    };

    if ($request->nomor_telepon !== $dataKasir->nomor_telepon) {
      $rules['nomor_telepon'] = 'required|unique:users';
    };

    if ($request->password) {
      $rules['password'] = 'required|min:5|max:255';
    }

    if ($request->is_admin) {
      $rules['is_admin'] = 'required';
    }

    $validatedData = $request->validate($rules);

    $validatedData['password'] = Hash::make($validatedData['password']);

    User::where('id', $request->id)->update($validatedData);

    return redirect('/dashboard/kasir')->with('success', 'Data kasir telah diupdate!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($user)
  {
    User::destroy($user->id);

    return redirect('/dashboard/kasir')->with('success', 'Data kasir telah dihapus!');
  }
}
