<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;

class SaldoAwalController extends Controller
{
    public function loadAllSaldoAwals(){
        $all_saldo_awals = SaldoAwal::all();
        return view('saldo-awal.index',compact('all_saldo_awals'));
    }

    public function loadAddSaldoAwalForm(){
        $all_saldo_awals = SaldoAwal::all();
        $barangs = barang::all(); // Fetch all barangs
        return view('saldo-awal.add-saldo-awal', compact('all_saldo_awals', 'barangs'));
    }

    public function AddSaldoAwal(Request $request)
    {
        // Perform form validation here
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tahun' => 'required|string',
            'bulan' => 'required|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'saldo_awal' => 'required',
            'total_terima' => 'required',
            'total_keluar' => 'required',
            'saldo_akhir' => 'required',
        ]);

        try {
            $new_saldo_awal = new SaldoAwal();
            $new_saldo_awal->barang_id = $request->barang_id;
            $new_saldo_awal->tahun = $request->tahun;
            $new_saldo_awal->bulan = $request->bulan;

            // Process the numeric inputs to remove any dots and convert to float
            $new_saldo_awal->saldo_awal = $this->parseNumber($request->saldo_awal);
            $new_saldo_awal->total_terima = $this->parseNumber($request->total_terima);
            $new_saldo_awal->total_keluar = $this->parseNumber($request->total_keluar);
            $new_saldo_awal->saldo_akhir = $this->parseNumber($request->saldo_akhir);

            $new_saldo_awal->save();

            return redirect('/saldo-awal')->with('success', 'Added Successfully');
        } catch (\Exception $e) {
            return redirect('/saldo-awal')->with('fail', $e->getMessage());
        }
    }

    /**
     * Helper function to parse numbers formatted with dots as thousands separators.
     */
    private function parseNumber($number)
    {
        return (float) str_replace('.', '', $number);
    }



    Public function search(Request $request)
    {
        $query = $request->input('query');

        // Search by the related 'nama_barang' from the 'barang' table
        $all_saldo_awals = SaldoAwal::whereHas('barang', function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%$query%");
            })
            ->orWhere('tahun', 'like', "%$query%")
            ->orWhere('bulan', 'like', "%$query%")
            ->orWhere('saldo_awal', 'like', "%$query%")
            ->orWhere('total_terima', 'like', "%$query%")
            ->orWhere('total_keluar', 'like', "%$query%")
            ->orWhere('saldo_akhir', 'like', "%$query%")
            ->get();

        // Return view with the search results
        return view('saldo-awal.index', compact('all_saldo_awals'));
    }

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(SaldoAwal $saldoAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaldoAwal $saldoAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaldoAwal $saldoAwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaldoAwal $saldoAwal)
    {
        //
    }
}
