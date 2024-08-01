<?php

use App\Models\LayananLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CatatTransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//dashboard

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth')->name('dashboard');

//login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/logout', [LoginController::class, 'logout']);

//kasir

Route::resource('/kasir', KasirController::class)->middleware('isAdmin');

//layanan

Route::resource('/layanan', LayananController::class)->middleware('isAdmin');

Route::get('/layananLog/history', [LayananController::class, 'history'])->name('layanan.history');

//catat transaksi
Route::get('/transaksiBaru', [CatatTransaksiController::class, 'index']);

Route::post('/transaksiBaru', [CatatTransaksiController::class, 'catat']);

//tampil list layanan
Route::get('/list-layanan', [CatatTransaksiController::class, 'layanan']);

//admin
Route::resource('transaksi', TransaksiController::class)->middleware('isAdmin');

Route::post('cariTglTransaksi', [TransaksiController::class, 'cariTanggal'])->middleware('isAdmin');