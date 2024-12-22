<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPengeluaranBarang;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

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
        // Validasi input dari form
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tahun' => 'required|string',
            'bulan' => 'required|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'saldo_awal' => 'required',
        ]);

        // Parsing angka agar formatnya sesuai untuk database
        $saldo_awal = (float) str_replace([',', '.'], ['', '.'], $request->input('saldo_awal'));
        $total_terima = (float) str_replace([',', '.'], ['', '.'], $request->input('total_terima'));
        $total_keluar = (float) str_replace([',', '.'], ['', '.'], $request->input('total_keluar'));        

        // Ambil total penerimaan barang (dari database jika diperlukan)
        $db_total_terima = DetailPenerimaanBarang::whereHas('penerimaanBarang', function ($query) use ($request) {
            $query->whereYear('tanggal', $request->tahun)
                ->whereMonth('tanggal', $request->bulan);
        })->where('barang_id', $request->barang_id)->sum('jumlah_diterima');

        // Ambil total pengeluaran barang (dari database jika diperlukan)
        $db_total_keluar = DetailPengeluaranBarang::whereHas('pengeluaranBarang', function ($query) use ($request) {
            $query->whereYear('tanggal', $request->tahun)
                ->whereMonth('tanggal', $request->bulan);
        })->where('barang_id', $request->barang_id)->sum('jumlah_keluar');

        // Gunakan data dari database jika input kosong
        $total_terima = $total_terima ?: $db_total_terima;
        $total_keluar = $total_keluar ?: $db_total_keluar;

        // Hitung saldo akhir
        $saldo_akhir = $saldo_awal + $total_terima - $total_keluar;

        try {
            // Simpan data ke database
            $new_saldo_awal = new SaldoAwal();
            $new_saldo_awal->barang_id = $request->barang_id;
            $new_saldo_awal->tahun = $request->tahun;
            $new_saldo_awal->bulan = $request->bulan;
            $new_saldo_awal->saldo_awal = number_format($saldo_awal, 2, '.', '');
            $new_saldo_awal->total_terima = number_format($total_terima, 2, '.', '');
            $new_saldo_awal->total_keluar = number_format($total_keluar, 2, '.', '');
            $new_saldo_awal->saldo_akhir = number_format($saldo_akhir, 2, '.', '');
            $new_saldo_awal->save();

            // Debugging untuk memeriksa data yang disimpan
            // $savedData = SaldoAwal::find($new_saldo_awal->id); // Ambil data dari database
            // dd($savedData); // Hentikan eksekusi dan tampilkan data

            return redirect('/saldo-awal')->with('success', 'Added Successfully');
        } catch (\Exception $e) {
            return redirect('/saldo-awal')->with('fail', $e->getMessage());
        }
    }

    public function getSaldoAwalDanTransaksi(Request $request)
    {
        $barangId = $request->query('barang_id');
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        // Validasi input
        if (!$barangId || !$bulan || !$tahun) {
            return response()->json([
                'saldo_awal' => 0,
                'total_terima' => 0,
                'total_keluar' => 0,
            ]);
        }

        // Hitung saldo akhir bulan sebelumnya (sebagai saldo awal)
        $previousMonth = $bulan == '01' ? '12' : str_pad($bulan - 1, 2, '0', STR_PAD_LEFT);
        $previousYear = $bulan == '01' ? $tahun - 1 : $tahun;

        $saldoAwal = DB::table('saldo_awals')
            ->where('barang_id', $barangId)
            ->where('tahun', $previousYear)
            ->where('bulan', $previousMonth)
            ->value('saldo_akhir') ?? 0;

        // Hitung total terima untuk bulan yang dipilih
        $totalTerima = DB::table('detail_penerimaan_barangs as dpb')
            ->join('master_penerimaan_barangs as mpb', 'dpb.master_penerimaan_barang_id', '=', 'mpb.id')
            ->where('dpb.barang_id', $barangId)
            ->whereYear('mpb.tanggal', $tahun)
            ->whereMonth('mpb.tanggal', $bulan)
            ->sum('dpb.jumlah_diterima');

        // Hitung total keluar untuk bulan yang dipilih
        $totalKeluar = DB::table('detail_pengeluaran_barangs as dpb')
            ->join('master_pengeluaran_barangs as mpb', 'dpb.master_pengeluaran_barang_id', '=', 'mpb.id')
            ->where('dpb.barang_id', $barangId)
            ->whereYear('mpb.tanggal', $tahun)
            ->whereMonth('mpb.tanggal', $bulan)
            ->sum('dpb.jumlah_keluar');

        return response()->json([
            'saldo_awal' => $saldoAwal,
            'total_terima' => $totalTerima,
            'total_keluar' => $totalKeluar,
        ]);
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
    
    public function detailSaldoAwal($barang_id)
    {
        // Ambil data saldo awal dan transaksi terkait berdasarkan barang_id
        $saldo_awal_details = SaldoAwal::where('barang_id', $barang_id)
            ->with(['barang']) // Pastikan relasi dengan barang di-load
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        // Kembalikan ke halaman detail saldo awal
        return view('saldo-awal.detail-saldo-awal', compact('saldo_awal_details'));
    }

}
