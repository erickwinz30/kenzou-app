<?php

use App\Models\LayananLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DateRangeController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MemberLoginController;
use App\Http\Controllers\CatatTransaksiController;
use App\Http\Controllers\MemberRegisterController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

//dashboard
Route::get('/dashboard/admin', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/dashboard/fetch-sales-data', [DashboardController::class, 'perHourSales'])->middleware('isAdmin')->name('fetch-sales-data');
Route::get('/dashboard/fetch-sales-this-month', [DashboardController::class, 'perDaySales'])->middleware('isAdmin')->name('fetch-sales-this-month');
Route::get('/dashboard/fetch-car-this-month', [DashboardController::class, 'perDayCars'])->middleware('isAdmin')->name('fetch-sales-this-month');
Route::get('/dashboard/fetch-sales-this-year', [DashboardController::class, 'perMonthSales'])->middleware('isAdmin')->name('fetch-sales-this-year');

//login
Route::get('dashboard/login', [LoginController::class, 'index'])->name('dashboard-login')->middleware('guest');
Route::post('/dashboard/login', [LoginController::class, 'authenticate']);
Route::post('/dashboard/logout', [LoginController::class, 'logout']);

//kasir
Route::resource('/dashboard/kasir', KasirController::class)->middleware('isAdmin');

//layanan

Route::resource('/dashboard/layanan', LayananController::class)->middleware('isAdmin');

Route::get('/dashboard/layananLog/history', [LayananController::class, 'history'])->name('layanan.history');

//dashboard kasir
Route::get('/dashboard/dashboard-kasir', [CatatTransaksiController::class, 'dashboardKasir'])->middleware('notAdmin')->name('dashboard-kasir');
Route::get('/dashboard/fetch-sales-cashier', [CatatTransaksiController::class, 'perHourSales'])->name('fetch-sales-cashier');

//catat transaksi
Route::get('/dashboard/transaksiBaru', [CatatTransaksiController::class, 'index'])->middleware('notAdmin');

Route::post('/dashboard/transaksiBaru', [CatatTransaksiController::class, 'catat'])->middleware('notAdmin');

//tampil list layanan
Route::get('/dashboard/list-layanan', [CatatTransaksiController::class, 'layanan'])->middleware('notAdmin');

//admin
Route::resource('dashboard/transaksi', TransaksiController::class)->middleware('isAdmin');

//dateRange
Route::get('/dashboard/transaksiThisWeek', [TransaksiController::class, 'thisWeek'])->middleware('isAdmin')->name('transaksi.thisWeek');
Route::get('/dashboard/transaksiThisMonth', [TransaksiController::class, 'thisMonth'])->middleware('isAdmin')->name('transaksi.thisMonth');
Route::get('/dashboard/transaksiThisYear', [TransaksiController::class, 'thisYear'])->middleware('isAdmin')->name('transaksi.thisYear');

//sisi member
//login
Route::get('/login', [MemberLoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [MemberLoginController::class, 'authenticate']);
Route::get('/logout', [MemberLoginController::class, 'logout']);

//register
Route::get('/register', [MemberRegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [MemberRegisterController::class, 'store']);

Route::middleware('member')->group(function () {
  Route::get('/', [MemberController::class, 'index'])->name('homepage');
});