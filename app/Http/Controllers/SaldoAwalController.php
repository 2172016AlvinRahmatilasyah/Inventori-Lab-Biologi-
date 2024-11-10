<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;

class SaldoAwalController extends Controller
{
    public function loadAllSaldoAwals(){
        $all_saldo_awals = SaldoAwal::with('barang')
            ->orderBy('barang_id') 
            ->orderBy('tahun') 
            ->orderByRaw('LPAD(bulan, 2, "0")') 
            ->get();
    
        return view('saldo-awal.index', compact('all_saldo_awals'));
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
        $saldo_awal = (float) str_replace(['.', ','], ['', '.'], $request->input('saldo_awal'));
        $total_terima = (float) str_replace(['.', ','], ['', '.'], $request->input('total_terima'));
        $total_keluar = (float) str_replace(['.', ','], ['', '.'], $request->input('total_keluar'));
        $saldo_akhir = (float) str_replace(['.', ','], ['', '.'], $request->input('saldo_akhir'));

        try {
            $new_saldo_awal = new SaldoAwal();
            $new_saldo_awal->barang_id = $request->barang_id;
            $new_saldo_awal->tahun = $request->tahun;
            $new_saldo_awal->bulan = $request->bulan;
            $new_saldo_awal->saldo_awal = $saldo_awal;
            $new_saldo_awal->total_terima = $total_terima;
            $new_saldo_awal->total_keluar = $total_keluar;
            $new_saldo_awal->saldo_akhir = $saldo_akhir;
            $new_saldo_awal->save();

            return redirect('/saldo-awal')->with('success', 'Added Successfully');
        } catch (\Exception $e) {
            return redirect('/saldo-awal')->with('fail', $e->getMessage());
        }
    }

    // /**
    //  * Helper function to parse numbers formatted with dots as thousands separators.
    //  */
    // private function parseNumber($number)
    // {
    //     return (float) str_replace('.', '', $number);
    // }



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

    public function getSaldoAkhirSebelum(Request $request)
    {
        $barangId = $request->query('barang_id');
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
    
        // Calculate previous month and year
        $previousMonth = $bulan == '01' ? '12' : str_pad($bulan - 1, 2, '0', STR_PAD_LEFT);
        $previousYear = $bulan == '01' ? $tahun - 1 : $tahun;
    
        // Fetch saldo_akhir for the previous month and year, or default to 0
        $saldoAkhir = SaldoAwal::where('barang_id', $barangId)
            ->where('tahun', $previousYear)
            ->where('bulan', $previousMonth)
            ->value('saldo_akhir') ?? 0;
    
        // Format saldo_akhir as decimal (15,2) before returning
        return response()->json(['saldo_akhir' => number_format((float)$saldoAkhir, 2, '.', '')]);
    }
    




 
}
