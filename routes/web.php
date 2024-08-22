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
Route::get('/dashboard/admin', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/dashboard/fetch-sales-data', [DashboardController::class, 'perHourSales'])->middleware('auth')->name('fetch-sales-data');
Route::get('/dashboard/fetch-sales-this-month', [DashboardController::class, 'perDaySales'])->middleware('auth')->name('fetch-sales-this-month');
Route::get('/dashboard/fetch-car-this-month', [DashboardController::class, 'perDayCars'])->middleware('auth')->name('fetch-sales-this-month');
Route::get('/dashboard/fetch-sales-this-year', [DashboardController::class, 'perMonthSales'])->middleware('auth')->name('fetch-sales-this-year');

//login
Route::get('dashboard/login', [LoginController::class, 'index'])->name('login')->middleware('guest');

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

//sisi pelanggan