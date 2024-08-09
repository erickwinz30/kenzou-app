<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $todayTransaksi = $this->countMobil();
        $todaySales = $this->todaySales();
        $thisMonthSales = $this->thisMonthSales();
        $recentTransaction = $this->recentTransaction();

        $dataPenjualanDashboard = $this->perHourSales($request);

        return view('dashboard.index', [
            'todayTransaksi' => $todayTransaksi,
            'todaySales' => $todaySales,
            'thisMonth' => $thisMonthSales,
            'salesData' => $dataPenjualanDashboard,
            'recentTransactions' => $recentTransaction,
        ]);
    }

    public function countMobil() {
        $today = Carbon::today()->toDateString();

        $todayTransaksi = Transaksi::whereDate('date', $today)->count();

        return $todayTransaksi;
    }

    public function todaySales() {
        $today = Carbon::today()->toDateString();

        $todaySales = Transaksi::whereDate('date', $today)->sum('total_harga');

        return $todaySales;
    }

    public function thisMonthSales() {
        //bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $thisMonthSales = Transaksi::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('total_harga');

        return $thisMonthSales;
    }

    public function perHourSales(Request $request) {
        $currentDate = Carbon::now()->format('Y-m-d');

        $results = DB::table('transaksis')
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00') as hour"),
                DB::raw('SUM(total_harga) as total_harga')
            )
            ->whereDate('date', $currentDate) // Filter by current date
            ->whereTime('date', '>=', '07:30:00')
            ->whereTime('date', '<=', '17:30:00')
            ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00')"))
            ->orderBy('hour')
            ->get();
        
        $results2 = DB::table('transaksis')
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00') as hour"),
                DB::raw('COUNT(id) as transaksi_id')
            )
            ->whereDate('date', $currentDate) // Filter by current date
            ->whereTime('date', '>=', '07:30:00')
            ->whereTime('date', '<=', '17:30:00')
            ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m-%d %H:00:00')"))
            ->orderBy('hour')
            ->get();

        $data = [];
        $startHour = Carbon::createFromTimeString('07:00:00');
        $endHour = Carbon::createFromTimeString('17:00:00');
        $currentHour = $startHour->copy();

        while ($currentHour->lte($endHour)) {
            $hourString = $currentHour->format('Y-m-d H:00:00');
            $totalHarga = 0;
            $transactionCount = 0;

            foreach ($results as $result) {
                if ($result->hour === $hourString) {
                    $totalHarga = $result->total_harga;
                    break;
                }
            }

            foreach ($results2 as $result) {
                if ($result->hour === $hourString) {
                    $transactionCount = $result->transaksi_id;
                    break;
                }
            }

            $data[] = [
                'hour' => $hourString,
                'total_harga' => $totalHarga,
                'jumlah_transaksi' => $transactionCount,
            ];

            $currentHour->addHour();
        }

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return $data;
    }

    public function recentTransaction() {
        $recentTransaction = Transaksi::orderBy('date', 'desc')->take(8)->get();

        return $recentTransaction;
    }
}
