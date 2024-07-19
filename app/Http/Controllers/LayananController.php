<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Layanan;
use Illuminate\Http\Request;
use App\Models\DetailLayanan;
use App\Models\LayananLog;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.layanan.index', [
            'layanans' => Layanan::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_layanan' => 'required|min:3|max:255',
            'harga' => 'required',
        ]);

        $validatedData['added_date'] = Carbon::now('Asia/Jakarta');

        // dd($validatedData);

        $layanan = Layanan::create($validatedData);

        $validatedData['layanan_id'] = $layanan->id;

        LayananLog::create($validatedData);

        return redirect('/layanan')->with('success', 'Layanan baru telah tertambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Layanan $layanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan)
    {
        Layanan::destroy($layanan->id);

        return redirect('/layanan')->with('success', 'Layanan telah terhapus!');
    }

    public function history()
    {
        return view('dashboard.layanan.history', [
            'layanans' => LayananLog::all(),
        ]);
    }
}
