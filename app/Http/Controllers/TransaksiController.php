<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.transaksi.index', [
            'transaksis' => Transaksi::all(),
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
    public function update(Request $request, string $id)
    {
        //
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
}
