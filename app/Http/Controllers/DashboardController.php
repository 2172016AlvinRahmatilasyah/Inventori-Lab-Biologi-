<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarang;
use App\Models\PengeluaranBarang;
use App\Models\Barang; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $barangMasukBulanIni = PenerimaanBarang::whereYear('created_at', Carbon::now()->year)
                                               ->whereMonth('created_at', Carbon::now()->month)
                                               ->count();

        $barangKeluarBulanIni = PengeluaranBarang::whereYear('created_at', Carbon::now()->year)
                                                 ->whereMonth('created_at', Carbon::now()->month)
                                                 ->count();

        $barangStokMinimal = Barang::where('stok', '<=', 20)->get();

        $today = Carbon::now();
        $twoMonthsLater = Carbon::now()->addMonths(2);
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', $twoMonthsLater)->get();

        $totalStok = Barang::sum('stok');
       
        return view('dashboard', compact('barangMasukBulanIni', 'barangKeluarBulanIni', 
                    'barangStokMinimal', 'barangKadaluarsaMendekati','totalStok'
        ));
    }
}

