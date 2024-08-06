<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DateRangeController extends Controller
{
    public function thisWeek() {
        try {
            // Menentukan awal dan akhir minggu ini
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            // Mengambil data transaksi dalam rentang tanggal minggu ini
            $transaksis = Transaksi::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->orderBy('date', 'desc')
                ->get();

            Log::info('This Week Data', ['transaksis' => $transaksis]);
            dd($transaksis);

            return view('dashboard.transaksi.index', [
                'transaksis' => $transaksis,
            ]);
        } catch(\Exception $e) {
            Log::error('This Week Data Error: ', ['message' => $e->getMessage()]);

            return redirect('/transaksi')->with('error', 'Terjadi kesalahan saat menghapus transaksi');
        }
        
    }
}
