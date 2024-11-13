<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\barang as ModelsBarang;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPengeluaranBarang;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Models\PenerimaanBarang;
use App\Models\PengeluaranBarang;
use App\Models\SaldoAwal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Existing code for data retrieval
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
        $totalSaldoAwalBulanIni = SaldoAwal::where('bulan', Carbon::now()->month)
                                             ->sum('saldo_awal');
        $totalSaldoTerimaBulanIni = SaldoAwal::where('bulan', Carbon::now()->month)
                                             ->sum('total_terima');
        $totalSaldoKeluarBulanIni = SaldoAwal::where('bulan', Carbon::now()->month)
                                             ->sum('total_keluar');
        
        return view('dashboard', compact(
            'barangMasukBulanIni',
            'barangKeluarBulanIni',
            'barangStokMinimal',
            'barangKadaluarsaMendekati',
            'totalStok',
            'totalSaldoAwalBulanIni',
            'totalSaldoTerimaBulanIni',
            'totalSaldoKeluarBulanIni'
        ));
    }

    public function generateReport()
    {
        $thisYear = Carbon::now()->year;
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Fetch detail transactions of this month for 'penerimaan' and 'pengeluaran'
        $detailPenerimaan = DetailPenerimaanBarang::with(['PenerimaanBarang.jenisPenerimaanBarang', 'PenerimaanBarang.user', 'PenerimaanBarang.supkonpro', 'barang'])
            ->whereHas('PenerimaanBarang', function($query) {
                $query->whereYear('created_at', Carbon::now()->year)
                      ->whereMonth('created_at', Carbon::now()->month);
            })->get();

        $detailPengeluaran = DetailPengeluaranBarang::with(['PengeluaranBarang.jenisPengeluaranBarang', 'PengeluaranBarang.user', 'PengeluaranBarang.supkonpro', 'barang'])
            ->whereHas('PengeluaranBarang', function($query) {
                $query->whereYear('created_at', Carbon::now()->year)
                      ->whereMonth('created_at', Carbon::now()->month);
            })->get();


        $barangKeluarBulanIni = DetailPengeluaranBarang::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with('barang')->get();
        // Query untuk barang masuk bulan ini
        $barangMasukBulanIni = DetailPenerimaanBarang::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with('barang')->get();
        $barangList = Barang::all()->map(function ($barang) use ($barangKeluarBulanIni, $barangMasukBulanIni) {
                $jumlahKeluar = $barangKeluarBulanIni->where('barang_id', $barang->id)->sum('jumlah_keluar');
                $jumlahMasuk = $barangMasukBulanIni->where('barang_id', $barang->id)->sum('jumlah_diterima');
        
        return [
            'nama_barang' => $barang->nama_barang,
            'satuan_stok' => $barang->jenisBarang->satuan_stok ?? 'N/A',
            'jumlah_keluar' => $jumlahKeluar ?: 0,
            'jumlah_masuk' => $jumlahMasuk ?: 0,
        ];
        });

        $allSaldoAwals = SaldoAwal::with('barang')
        ->where('bulan', Carbon::now()->month)
        ->where('tahun', $thisYear)
        ->get();


        $allBarangs = Barang::with('jenisBarang')->get();

        // Data for the PDF report
        $data = [
            'title' => 'Laporan Keseluruhan',
            'date' => Carbon::now()->format('Y-m-d'),
            'user' => Auth::user()->name,
            'barangMasukBulanIni' => PenerimaanBarang::whereYear('created_at', Carbon::now()->year)
                                                      ->whereMonth('created_at', Carbon::now()->month)
                                                      ->count(),
            'barangKeluarBulanIni' => PengeluaranBarang::whereYear('created_at', Carbon::now()->year)
                                                       ->whereMonth('created_at', Carbon::now()->month)
                                                       ->count(),
            'barangStokMinimal' => Barang::where('stok', '<=', 20)->get(),
            'barangKadaluarsaMendekati' => Barang::where('kadaluarsa', '<=', Carbon::now()->addMonths(2))->get(),
            'totalStok' => Barang::sum('stok'),
            'detail_penerimaan' => $detailPenerimaan,
            'detail_pengeluaran' => $detailPengeluaran,
            'barangList' => $barangList,
            'all_barangs' => $allBarangs,
            'totalSaldoAwalBulanIni' => SaldoAwal::where('bulan', Carbon::now()->month)
                                        ->sum('saldo_awal'),
            'totalSaldoTerimaBulanIni' => SaldoAwal::where('bulan', Carbon::now()->month)
                                          ->sum('total_terima'),
            'totalSaldoKeluarBulanIni' => SaldoAwal::where('bulan', Carbon::now()->month)
                                             ->sum('total_keluar'),
            'all_saldo_awals'=>$allSaldoAwals,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-keseluruhan', $data);
        return $pdf->download('Laporan_Keseluruhan.pdf');
    }

    public function showBarangMasukBulanIni()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $detailPenerimaan = DetailPenerimaanBarang::with([
            'PenerimaanBarang.jenisPenerimaanBarang',
            'PenerimaanBarang.user',
            'PenerimaanBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->get();


        return view('laporan.laporan-barang-masuk', compact('detailPenerimaan'));
    }

    public function showBarangKeluarBulanIni()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $detailPengeluaran = DetailPengeluaranBarang::with([
            'PengeluaranBarang.jenisPengeluaranBarang',
            'PengeluaranBarang.user',
            'PengeluaranBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->get();


        return view('laporan.laporan-barang-keluar', compact('detailPengeluaran'));
    }

    public function showPerubahanPersediaanBulanIni()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $detailPenerimaan = DetailPenerimaanBarang::with([
            'PenerimaanBarang.jenisPenerimaanBarang',
            'PenerimaanBarang.user',
            'PenerimaanBarang.supkonpro',
            'barang'
        ]) 
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->get();
        
        $detailPengeluaran = DetailPengeluaranBarang::with([
            'PengeluaranBarang.jenisPengeluaranBarang',
            'PengeluaranBarang.user',
            'PengeluaranBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->get();


        return view('laporan.laporan-perubahan-persediaan', compact('detailPenerimaan', 
                    'detailPengeluaran'));
    }

}