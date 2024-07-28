<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DetailLayanan;
use Illuminate\Support\Facades\Auth;

class CatatTransaksiController extends Controller
{
    public function index() {
        return view('dashboard.kasir.transaksi', [
            'layanans' => Layanan::all(),
        ]);
    }

    public function catat(Request $request) {
        try {
            $validatedData = $request->validate([
                'nomor_telepon' => 'required|min:10|max:15',
                'keterangan' => 'max:255',
                'total_harga' => 'required',
                'metode_pembayaran' => 'required',
            ]);

            $validatedData['user_id'] = Auth::user()->id;
            $validatedData['date'] = Carbon::now('Asia/Jakarta');

            // Log validated data
            Log::info('Validated Data:', $validatedData);

            // Create new transaction
            $transaction = Transaksi::create($validatedData);
            // Log created transaction

            // dd($transaction->id);

            Log::info('Created Transaction:', $transaction->toArray());

            //catat detail layanan

            $validatedData2 = $request->validate([
                'layanan_id' => 'required|array',
                'layanan_id.*' => 'exists:layanans,id',
            ]);

            foreach($validatedData2['layanan_id'] as $layananId) {
                $detailLayanan = DetailLayanan::create([
                'transaksi_id' => $transaction->id, // UUID
                'layanan_id' => $layananId,
            ]);

            // Log each detail layanan
            Log::info('Detail Layanan Created:', ['detail_layanan' => $detailLayanan]);
        }

            return redirect('/transaksiBaru')->with('success', 'Data transaksi telah tertambah!!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Transaction Creation Error:', ['message' => $e->getMessage()]);

            return redirect('transaksiBaru')->back()->withErrors('Terjadi kesalahan saat mencatat transaksi.');
        }

        // $validatedData = $request->validate([
        //     'nomor_telepon' => 'required|min:10|max:15',
        //     'keterangan' => 'max:255',
        //     'total_harga' => 'required',
        //     'metode_pembayaran' => 'required',
        // ]);

        // $validatedData['user_id'] = Auth::user()->id;

        // $validatedData['date'] = Carbon::now('Asia/Jakarta');

        // $catatTransaksi = Transaksi::create($validatedData);
        // dd($catatTransaksi);

        // Log::info(Transaksi::create($validatedData));

        // dd($catatTransaksi->id);

        // $validatedData2 = $request->validate([
        //     'layanan_id' => 'required',
        //     'date' => Carbon::now('Asia/Jakarta'),
        // ]);

        // $validatedData2['id'] = $catatTransaksi->id;

        // foreach($validatedData2['layanan_id'] as $idLayanan) {
        //     echo($idLayanan);
        // }

        // dd($layananId);

        return redirect()->with('success', 'Data transaksi telah tertambah!!');
    }
}
