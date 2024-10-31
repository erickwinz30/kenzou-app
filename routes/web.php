<?php

use App\Models\LayananLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DateRangeController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MemberLoginController;
use App\Http\Controllers\OwnedVoucherController;
use App\Http\Controllers\CatatTransaksiController;
use App\Http\Controllers\MemberRegisterController;
use App\Http\Controllers\BadgeLeaderboardController;

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

Route::middleware('isAdmin')->group(function () {
  //dashboard
  // Route::get('/dashboard/admin', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
  Route::get('/dashboard/admin', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/dashboard/fetch-sales-data', [DashboardController::class, 'perHourSales'])->name('fetch-sales-data');
  Route::get('/dashboard/fetch-sales-this-month', [DashboardController::class, 'perDaySales'])->name('fetch-sales-this-month');
  Route::get('/dashboard/fetch-car-this-month', [DashboardController::class, 'perDayCars'])->name('fetch-sales-this-month');
  Route::get('/dashboard/fetch-sales-this-year', [DashboardController::class, 'perMonthSales'])->name('fetch-sales-this-year');

  //dashboard transaksi
  Route::resource('dashboard/transaksi', TransaksiController::class);

  //dateRange
  Route::get('/dashboard/transaksiThisWeek', [TransaksiController::class, 'thisWeek'])->name('transaksi.thisWeek');
  Route::get('/dashboard/transaksiThisMonth', [TransaksiController::class, 'thisMonth'])->name('transaksi.thisMonth');
  Route::get('/dashboard/transaksiThisYear', [TransaksiController::class, 'thisYear'])->name('transaksi.thisYear');

  //layanan
  Route::resource('/dashboard/layanan', LayananController::class);
  Route::get('/dashboard/layananLog', [LayananController::class, 'history'])->name('layanan.history');

  //kasir
  Route::resource('/dashboard/kasir', KasirController::class);

  //pelanggan
  Route::resource('/dashboard/pelanggan', PelangganController::class);
  Route::get('/dashboard/memberFetch', [PelangganController::class, 'memberFetch'])->name('member.fetch');
  Route::get('/dashboard/pelangganFetch', [PelangganController::class, 'pelangganFetch'])->name('pelanggan.fetch');

  //badge
  Route::resource('/dashboard/badge', BadgeController::class);

  //badge leaderboard
  Route::resource('/dashboard/badge-leaderboard', BadgeLeaderboardController::class);

  //voucher
  Route::resource('/dashboard/voucher', VoucherController::class);
  Route::get('/dashboard/nonActiveFetch', [VoucherController::class, 'nonActiveFetch'])->name('voucher.nonActiveFetch');
  Route::get('/dashboard/allVoucherFetch', [VoucherController::class, 'allVoucherFetch'])->name('voucher.allVoucherFetch');
  Route::post('/dashboard/toggle-voucher-activation', [VoucherController::class, 'toggleActivation'])->name('voucher.toggleActivation');


  //challenge & reward unit
  Route::resource('/dashboard/challenge', ChallengeController::class);
  Route::get('/dashboard/challenge-active-fetch', [ChallengeController::class, 'activeFetch'])->name('challenge.activeFetch');
  Route::get('/dashboard/challenge-nonactive-fetch', [ChallengeController::class, 'nonActiveFetch'])->name('challenge.nonActiveFetch');
  Route::post('/dashboard/toggle-challenge-activation', [ChallengeController::class, 'toggleActivation'])->name('challenge.toggleActivation');
  Route::get('/dashboard/fetchUnit', [UnitController::class, 'index']);
  Route::get('/dashboard/fetchUnits', [UnitController::class, 'search'])->name('fetch.unit');
  Route::post('/dashboard/fetchUnit/search', [UnitController::class, 'search']);
});

Route::middleware('notAdmin')->group(function () {
  //dashboard kasir
  Route::get('/dashboard', [CatatTransaksiController::class, 'dashboardKasir'])->name('dashboard-kasir');
  Route::get('/dashboard/fetch-sales-cashier', [CatatTransaksiController::class, 'perHourSales'])->name('fetch-sales-cashier');

  //catat transaksi
  Route::get('/dashboard/transaksiBaru', [CatatTransaksiController::class, 'index']);

  Route::post('/dashboard/transaksiBaru', [CatatTransaksiController::class, 'catat']);
  Route::post('/dashboard/transaksiBaru/nomor_telepon', [CatatTransaksiController::class, 'searchPhoneNumber']);

  //tampil list layanan
  Route::get('/dashboard/list-layanan', [CatatTransaksiController::class, 'layanan']);
});

//login
Route::get('dashboard/login', [LoginController::class, 'index'])->name('dashboard-login')->middleware('guest');
Route::post('/dashboard/login', [LoginController::class, 'authenticate']);
Route::get('dashboard/admin-register', [AdminController::class, 'index'])->name('admin-register')->middleware('guest');
Route::post('dashboard/admin-register', [AdminController::class, 'authenticate']);
Route::post('/dashboard/logout', [LoginController::class, 'logout']);


//sisi member
//login
Route::get('/login', [MemberLoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [MemberLoginController::class, 'authenticate']);
Route::get('/logout', [MemberLoginController::class, 'logout']);

//register
Route::get('/register', [MemberRegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [MemberRegisterController::class, 'store']);

//google login
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth:member')->group(function () {
  Route::get('/', [MemberController::class, 'index'])->name('homepage');
  Route::get('/register/next', [GoogleController::class, 'viewAfterGoogleCallback'])->name('register-next');
  Route::post('/register/next', [GoogleController::class, 'nextRegisterStore']);

  //voucher
  Route::get('/voucher', [OwnedVoucherController::class, 'index'])->name('voucher-index');
  Route::post('/voucher/claim', [OwnedVoucherController::class, 'claimVoucher']);

  //account information page
  Route::get('/account', [MemberController::class, 'account'])->name('account');
  Route::get('/account/edit', [MemberController::class, 'viewAccountEdit'])->name('account-edit');
  Route::post('/account/edit/post', [MemberController::class, 'accountUpdate']);
  Route::get('/account/transaction-history', [MemberController::class, 'transactionHistory'])->name('transaction-history');
  Route::get('/account/view-transaction-history/{transaksi:id}', [MemberController::class, 'viewTransactionHistory']);
  Route::get('/account/point-history', [MemberController::class, 'pointHistory'])->name('point-history');
});
