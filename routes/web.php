<?php

use App\Models\LayananLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DateRangeController;
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
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/fetch-sales-data', [DashboardController::class, 'index'])->name('fetch-sales-data');

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
Route::get('/transaksiBaru', [CatatTransaksiController::class, 'index'])->middleware('notAdmin');

Route::post('/transaksiBaru', [CatatTransaksiController::class, 'catat'])->middleware('notAdmin');

//tampil list layanan
Route::get('/list-layanan', [CatatTransaksiController::class, 'layanan'])->middleware('notAdmin');

//admin
Route::resource('transaksi', TransaksiController::class)->middleware('isAdmin');

//dateRange
Route::get('/transaksiThisWeek', [TransaksiController::class, 'thisWeek'])->middleware('isAdmin')->name('transaksi.thisWeek');
Route::get('/transaksiThisMonth', [TransaksiController::class, 'thisMonth'])->middleware('isAdmin')->name('transaksi.thisMonth');
Route::get('/transaksiThisYear', [TransaksiController::class, 'thisYear'])->middleware('isAdmin')->name('transaksi.thisYear');