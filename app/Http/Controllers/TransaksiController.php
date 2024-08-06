<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailLayanan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();

        return view('dashboard.transaksi.index', [
            'transaksis' => Transaksi::whereDate('date', $today)
            ->orderBy('date', 'desc')
            ->get(),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        return view('dashboard.transaksi.edit', [
            'transaksi' => $transaksi,
            'users' => User::all(),
            'layanans' => Layanan::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'nomor_telepon' => 'required|min:10|max:15',
                'date' => 'required',
                'metode_pembayaran' => 'required',
                'keterangan' => 'max:255',
                'total_harga' => 'required',
            ];

            $validatedDataTransaksi = $request->validate($rules);

            Transaksi::where('id', $transaksi->id)->update($validatedDataTransaksi);

            // Retrieve the updated transaction
            $updatedTransaction = Transaksi::find($transaksi->id);

            Log::info('Transaction Updated: ', ['transaction' => $updatedTransaction->toArray()]);
            
            $rules2 = [
                'layanan' => 'array',
                'layanan.*' => 'nullable|:layanans,id',
            ];

            $validatedDataLayanan = $request->validate($rules2);

            $existingDetailLayanan = $transaksi->detail_layanan;

            foreach ($validatedDataLayanan['layanan'] as $index => $layananId) {
            if (isset($existingDetailLayanan[$index])) {
                if (empty($layananId)) {
                    // Delete the detail_layanan entry if the option is empty
                    $existingDetailLayanan[$index]->delete();
                    Log::info('Detail Layanan Deleted: ', ['id' => $existingDetailLayanan[$index]->id]);
                } else {
                    // Update the detail_layanan entry if the option is not empty
                    $existingDetailLayanan[$index]->update([
                        'layanan_id' => $layananId,
                    ]);
                    Log::info('Detail Layanan Updated: ', ['layanan' => $existingDetailLayanan[$index]->toArray()]);
                }
            } else {
                if (!empty($layananId)) {
                    // Create a new detail_layanan entry if it does not exist and the option is not empty
                    $newDetailLayanan = $transaksi->detail_layanan()->create([
                        'layanan_id' => $layananId,
                        'transaksi_id' => $transaksi->id, // Ensure the correct transaksi_id is assigned
                    ]);
                    Log::info('Detail Layanan Created: ', ['layanan' => $newDetailLayanan->toArray()]);
                }
            }
        }   
            return redirect('/transaksi')->with('success', 'Data transaksi telah diupdate!!');
        } catch (\Exception $e) {
            Log::error('Transaction Update Error: ', ['message' => $e->getMessage()]);

            return redirect('/transaksi')->with('error', 'Terjadi kesalahan saat menghapus transaksi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            $deletedTransaction = Transaksi::destroy($transaksi->id);

            Log::info('Transaction Deleted: ', ['transaction' => $deletedTransaction]);

            return redirect('/transaksi')->with('success', 'Transaksi telah terhapus!!!');
        } catch (\Exception $e) {
            Log::error('Transaction Creation Error: ', ['message' => $e->getMessage()]);

            return redirect('/transaksi')->with('error', 'Terjadi kesalahan saat menghapus transaksi');
        }
    }

    public function thisWeek() {
        try {
            // Menentukan awal dan akhir minggu ini
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            // Mengambil data transaksi dalam rentang tanggal minggu ini
            $transaksis = Transaksi::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->orderBy('date', 'desc')
                ->get();

            return view('dashboard.transaksi.index', [
                'transaksis' => $transaksis,
            ]);
        } catch(\Exception $e) {
            Log::error('This Week Data Error: ', ['message' => $e->getMessage()]);

            return redirect('/transaksi')->with('error', 'Terjadi kesalahan saat menghapus transaksi');
        }
        
    }

    public function thisMonth() {
        try {
            // Menentukan awal dan akhir minggu ini
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Mengambil data transaksi dalam rentang tanggal minggu ini
            $transaksis = Transaksi::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->orderBy('date', 'desc')
                ->get();

            return view('dashboard.transaksi.index', [
                'transaksis' => $transaksis,
            ]);
        } catch(\Exception $e) {
            Log::error('This Month Data Error: ', ['message' => $e->getMessage()]);

            return redirect('/transaksi')->with('error', 'Terjadi kesalahan saat mengambil data per bulan');
        }
        
    }

    public function thisYear() {
        try {
            // Menentukan awal dan akhir minggu ini
            $startOfYear = Carbon::now()->startOfYear();
            $endOfYear = Carbon::now()->endOfYear();

            // Mengambil data transaksi dalam rentang tanggal minggu ini
            $transaksis = Transaksi::whereBetween('date', [$startOfYear, $endOfYear])
                ->orderBy('date', 'desc')
                ->get();

            return view('dashboard.transaksi.index', [
                'transaksis' => $transaksis,
            ]);
        } catch(\Exception $e) {
            Log::error('This Year Data Error: ', ['message' => $e->getMessage()]);

            return redirect('/transaksi')->with('error', 'Terjadi kesalahan saat mengambil data per tahun');
        }
        
    }
}
