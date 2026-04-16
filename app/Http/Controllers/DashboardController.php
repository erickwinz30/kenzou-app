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
  private function sqlDateFormatExpression(string $column, string $mysqlFormat, string $pgsqlFormat, bool $truncateHour = false): string
  {
    $driver = DB::connection()->getDriverName();

    if ($driver === 'pgsql') {
      if ($truncateHour) {
        return "TO_CHAR(DATE_TRUNC('hour', {$column}), '{$pgsqlFormat}')";
      }

      return "TO_CHAR({$column}, '{$pgsqlFormat}')";
    }

    return "DATE_FORMAT({$column}, '{$mysqlFormat}')";
  }

  public function index()
  {
    $todayTransaksi = $this->countMobil();
    $yesterdayCarPercentage = $this->yesterdayCountMobil();
    $todaySales = $this->todaySales();
    $yesterdaySalesPercentage = $this->yesterdaySalesPercentage();
    $thisMonthSales = $this->thisMonthSales();
    $lastMonthSales = $this->lastMonthSales();
    $recentTransaction = $this->recentTransaction();

    return view('dashboard.index', [
      'todayTransaksi' => $todayTransaksi,
      'yesterdayCarPercentage' => round($yesterdayCarPercentage, 2),
      'todaySales' => $todaySales,
      'yesterdaySalesPercentage' => round($yesterdaySalesPercentage, 2),
      'thisMonth' => $thisMonthSales,
      'lastMonth' => round($lastMonthSales, 2),
      'recentTransactions' => $recentTransaction,
    ]);
  }

  private function countMobil()
  {
    $today = Carbon::today()->toDateString();

    $todayTransaksi = Transaksi::whereDate('date', $today)->count();

    return $todayTransaksi;
  }

  private function yesterdayCountMobil()
  {
    $yesterday = Carbon::yesterday()->toDateString();
    $yesterdayCar = Transaksi::whereDate('date', $yesterday)->count();

    $todayCar = $this->countMobil();

    // Calculate the percentage change
    if ($yesterdayCar > 0) {
      $yesterdayCarPercentage = (($todayCar - $yesterdayCar) / $yesterdayCar) * 100;

      return $yesterdayCarPercentage;
    } else {
      // Handle cases where yesterday's sales were 0 to avoid division by zero
      $yesterdayCarPercentage = $todayCar > 0 ? 100 : 0;
      return $yesterdayCarPercentage;
    }
  }

  private function todaySales()
  {
    $today = Carbon::today()->toDateString();

    $todaySales = Transaksi::whereDate('date', $today)->sum('subtotal');

    return $todaySales;
  }

  private function yesterdaySalesPercentage()
  {
    $yesterday = Carbon::yesterday()->toDateString();
    $yesterdaySales = Transaksi::whereDate('date', $yesterday)->sum('subtotal');

    $todaySales = $this->todaySales();

    // Calculate the percentage change
    if ($yesterdaySales > 0) {
      $yesterdaySalesPercentage = (($todaySales - $yesterdaySales) / $yesterdaySales) * 100;

      return $yesterdaySalesPercentage;
    } else {
      // Handle cases where yesterday's sales were 0 to avoid division by zero
      $yesterdaySalesPercentage = $todaySales > 0 ? 100 : 0;
      return $yesterdaySalesPercentage;
    }
  }

  private function thisMonthSales()
  {
    //bulan ini
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();

    $thisMonthSales = Transaksi::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('subtotal');

    return $thisMonthSales;
  }

  private function lastMonthSales()
  {
    // Bulan kemarin
    $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
    $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

    $lastMonthSales = Transaksi::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->sum('subtotal');

    $thisMonthSales = $this->thisMonthSales();

    // Calculate the percentage change
    if ($lastMonthSales > 0) {
      $lastMonthSalesPercentage = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;

      return $lastMonthSalesPercentage;
    } else {
      // Handle cases where yesterday's sales were 0 to avoid division by zero
      $lastMonthSalesPercentage = $thisMonthSales > 0 ? 100 : 0;
      return $lastMonthSalesPercentage;
    }
  }

  public function perHourSales(Request $request)
  {
    $currentDate = Carbon::now()->format('Y-m-d');
    $hourExpression = $this->sqlDateFormatExpression('date', '%Y-%m-%d %H:00:00', 'YYYY-MM-DD HH24:00:00', true);

    $results = DB::table('transaksis')
      ->select(
        DB::raw("{$hourExpression} as hour"),
        DB::raw('SUM(subtotal) as subtotal')
      )
      ->whereDate('date', $currentDate) // Filter by current date
      ->whereTime('date', '>=', '07:30:00')
      ->whereTime('date', '<=', '17:30:00')
      ->groupBy(DB::raw($hourExpression))
      ->orderBy('hour')
      ->get();

    $results2 = DB::table('transaksis')
      ->select(
        DB::raw("{$hourExpression} as hour"),
        DB::raw('COUNT(id) as transaksi_id')
      )
      ->whereDate('date', $currentDate) // Filter by current date
      ->whereTime('date', '>=', '07:30:00')
      ->whereTime('date', '<=', '17:30:00')
      ->groupBy(DB::raw($hourExpression))
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
          $totalHarga = $result->subtotal;
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
        'subtotal' => $totalHarga,
        'jumlah_transaksi' => $transactionCount,
      ];

      $currentHour->addHour();
    }

    if ($request->wantsJson()) {
      return response()->json($data);
    }

    return $data;
  }

  private function recentTransaction()
  {
    $recentTransaction = Transaksi::orderBy('date', 'desc')->take(8)->get();

    return $recentTransaction;
  }

  public function perDaySales(Request $request)
  {
    // $currentMonth = Carbon::now()->format('Y-m'); // Get current month and year
    $dayExpression = $this->sqlDateFormatExpression('date', '%Y-%m-%d', 'YYYY-MM-DD');

    // Query for total sales per day
    $results = DB::table('transaksis')
      ->select(
        DB::raw("{$dayExpression} as day"),
        DB::raw('SUM(subtotal) as subtotal')
      )
      ->whereYear('date', Carbon::now()->year) // Filter by current year
      ->whereMonth('date', Carbon::now()->month) // Filter by current month
      ->groupBy(DB::raw($dayExpression))
      ->orderBy('day')
      ->get();

    // Initialize the data array
    $data = [];
    $startDay = Carbon::now()->startOfMonth(); // Start from the first day of the month
    $endDay = Carbon::now()->endOfMonth(); // End on the last day of the month
    $currentDay = $startDay->copy();

    while ($currentDay->lte($endDay)) {
      $dayString = $currentDay->format('Y-m-d');
      $totalHarga = 0;

      foreach ($results as $result) {
        if ($result->day === $dayString) {
          $totalHarga = $result->subtotal;
          break;
        }
      }

      $data[] = [
        'day' => $dayString,
        'subtotal' => $totalHarga,
      ];

      $currentDay->addDay();
    }

    if ($request->wantsJson()) {
      return response()->json($data);
    }

    return $data;
  }

  public function perDayCars(Request $request)
  {
    $dayExpression = $this->sqlDateFormatExpression('date', '%Y-%m-%d', 'YYYY-MM-DD');

    // Query for total transaction count per day
    $results2 = DB::table('transaksis')
      ->select(
        DB::raw("{$dayExpression} as day"),
        DB::raw('COUNT(id) as transaksi_id')
      )
      ->whereYear('date', Carbon::now()->year) // Filter by current year
      ->whereMonth('date', Carbon::now()->month) // Filter by current month
      ->groupBy(DB::raw($dayExpression))
      ->orderBy('day')
      ->get();

    $data = [];
    $startDay = Carbon::now()->startOfMonth(); // Start from the first day of the month
    $endDay = Carbon::now()->endOfMonth(); // End on the last day of the month
    $currentDay = $startDay->copy();

    while ($currentDay->lte($endDay)) {
      $dayString = $currentDay->format('Y-m-d');
      $transactionCount = 0;

      foreach ($results2 as $result) {
        if ($result->day === $dayString) {
          $transactionCount = $result->transaksi_id;
          break;
        }
      }

      $data[] = [
        'day' => $dayString,
        'jumlah_transaksi' => $transactionCount,
      ];

      $currentDay->addDay();
    }

    if ($request->wantsJson()) {
      return response()->json($data);
    }

    return $data;
  }

  public function perMonthSales(Request $request)
  {
    $monthExpression = $this->sqlDateFormatExpression('date', '%Y-%m', 'YYYY-MM');

    // Query for total sales per month
    $results = DB::table('transaksis')
      ->select(
        DB::raw("{$monthExpression} as month"),
        DB::raw('SUM(subtotal) as subtotal')
      )
      ->whereYear('date', Carbon::now()->year) // Filter by the current year
      ->groupBy(DB::raw($monthExpression))
      ->orderBy('month')
      ->get();

    $data = [];
    $startYear = Carbon::now()->startOfYear(); // Start from the first day of the year
    $endYear = Carbon::now()->endOfYear(); // End on the last day of the year
    $currentMonth = $startYear->copy();

    while ($currentMonth->lte($endYear)) {
      $monthString = $currentMonth->format('Y-m');
      $totalHarga = 0;

      foreach ($results as $result) {
        if ($result->month === $monthString) {
          $totalHarga = $result->subtotal;
          break;
        }
      }

      $data[] = [
        'month' => $monthString,
        'subtotal' => $totalHarga,
      ];

      $currentMonth->addMonth(); // Move to the next month
    }

    if ($request->wantsJson()) {
      return response()->json($data);
    }

    return $data;
  }

  public function perMonthLayanan(Request $request)
  {
    $monthShortExpression = $this->sqlDateFormatExpression('transaksis.date', '%b', 'Mon');

    // Query for total sales per month
    $results = DB::table('detail_layanans')
      ->join('layanans', 'detail_layanans.layanan_id', '=', 'layanans.id')
      ->join('transaksis', 'detail_layanans.transaksi_id', '=', 'transaksis.id')
      ->select(
        DB::raw("{$monthShortExpression} AS month"),
        'layanans.nama_layanan',
        DB::raw('COUNT(detail_layanans.id) AS jumlah_penggunaan'),
        DB::raw("MIN(transaksis.date) as first_date_of_month")
      )
      ->whereYear('transaksis.date', Carbon::now()->year)
      ->groupBy(
        DB::raw($monthShortExpression),
        'layanans.nama_layanan'
      )
      ->orderBy('first_date_of_month')
      ->get();


    $data = [];

    foreach ($results as $result) {
      $data[] = [
        'month' => $result->month,
        'nama_layanan' => $result->nama_layanan,
        'jumlah_penggunaan' => $result->jumlah_penggunaan
      ];
    }

    Log::info('Data Layanan: ', ['data' => $data]);

    if ($request->wantsJson()) {
      return response()->json($data);
    }

    return $data;
  }
}
