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
            'detail' => 'required|max:255',
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
        return view('dashboard.layanan.edit', [
            'layanan' => $layanan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        $rules = [
            'nama_layanan' => 'required|min:3|max:255',
            'harga' => 'required',
            'detail' => 'required|max:255',
        ];

        $validatedData = $request->validate($rules);
        
        Layanan::where('id', $layanan->id)->update($validatedData);

        $validatedData['updated_date'] = Carbon::now('Asia/Jakarta');

        LayananLog::where('layanan_id', $layanan->id)->update($validatedData);

        return redirect('/layanan')->with('success', 'Layanan telah diupdate!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan)
    {
        $validatedData['deleted_date'] = Carbon::now('Asia/Jakarta');

        LayananLog::where('layanan_id', $layanan->id)->update($validatedData);

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
