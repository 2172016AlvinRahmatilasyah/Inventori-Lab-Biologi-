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

    private function getDateRange(Request $request, $filter = 'current_month')
    {
        // Default values
        $startDate = null;
        $endDate = null;
        $thisYear = Carbon::now()->year;
        $thisMonth = Carbon::now()->month;

        // Handle custom dates filter
        if ($filter === 'custom_dates') {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
        } else {
            // Initialize start and end dates based on other filters
            switch ($filter) {
                case 'current_month':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'current_year':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
                case 'year_to_date':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now();
                    break;
                case 'last_30_days':
                    $startDate = Carbon::now()->subDays(30);
                    $endDate = Carbon::now();
                    break;
                case 'last_60_days':
                    $startDate = Carbon::now()->subDays(60);
                    $endDate = Carbon::now();
                    break;
                case 'last_90_days':
                    $startDate = Carbon::now()->subDays(90);
                    $endDate = Carbon::now();
                    break;
                case 'previous_month':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'previous_year':
                    $startDate = Carbon::now()->subYear()->startOfYear();
                    $endDate = Carbon::now()->subYear()->endOfYear();
                    break;
                case 'last_12_months':
                    $startDate = Carbon::now()->subMonths(12)->startOfMonth();
                    $endDate = Carbon::now();
                    break;
                case 'month_to_date':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now();
                    break;
                default:
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
            }
        }

        return compact('startDate', 'endDate', 'thisYear', 'thisMonth');
    }

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        //Fetch data berdasarkan rentang tanggal yang dipilih
        $barangMasuk = PenerimaanBarang::whereBetween('tanggal', [$startDate, $endDate])->count();
        $barangKeluar = PengeluaranBarang::whereBetween('tanggal', [$startDate, $endDate])->count();
        $totalPerubahanPersediaan = $barangMasuk + $barangKeluar;
      
        // Mengambil data stok minimal dan kadaluarsa
        $barangStokMinimal = Barang::where('stok', '<=', 20)->get();
        $twoMonthsLater = Carbon::now()->addMonths(2);
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', $twoMonthsLater)->get();
        $totalStok = Barang::sum('stok');

        // Mengambil data saldo awal
        $allSaldoAwals = SaldoAwal::with('barang')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Menghitung total saldo
        $totalSaldoAwal = $allSaldoAwals->sum('saldo_awal');
        $totalSaldoTerima = $allSaldoAwals->sum('total_terima');
        $totalSaldoKeluar = $allSaldoAwals->sum('total_keluar');

        // Prepare data untuk laporan
        $data = [
            'title' => 'Laporan',
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangMasuk' => $barangMasuk,
            'barangKeluar' => $barangKeluar,
            'totalStok' => $totalStok,
            'barangList' => $this->getBarangList($barangMasuk, $barangKeluar),
            'barangStokMinimal' => $barangStokMinimal,
            'barangKadaluarsaMendekati' => $barangKadaluarsaMendekati,
            'totalSaldoAwal' => $totalSaldoAwal,
            'totalSaldoTerima' => $totalSaldoTerima,
            'totalSaldoKeluar' => $totalSaldoKeluar,
            'totalPerubahanPersediaan' => $totalPerubahanPersediaan,
            'allSaldoAwals' => $allSaldoAwals, // Kirim data yang sudah dimodifikasi
        ];

        // Return the view
        return view('dashboard', $data);
    }

    public function showBarangMasuk(Request $request)
    {
        // Mengambil filter dari request
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Mengambil data barang masuk sesuai rentang tanggal yang dipilih
        $barangMasuk = DetailPenerimaanBarang::with('barang')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc') 
            ->get();
        $all_master_penerimaans = PenerimaanBarang::with('barang')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        // Siapkan data untuk laporan
        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangMasuk' => $barangMasuk,
            'penerimaanBarang' => $all_master_penerimaans,
        ];

        return view('laporan.laporan-barang-masuk', $data);
    }


    public function downloadBarangMasukPdf(Request $request)
    {
        // Mengambil filter dari request
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Mengambil data barang masuk sesuai rentang tanggal yang dipilih
        $barangMasuk = DetailPenerimaanBarang::with('barang')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc') 
            ->get();

        // Siapkan data untuk laporan
        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangMasuk' => $barangMasuk,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-barang-masuk-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-barang-masuk-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }


    public function showBarangKeluar(Request $request)
    {
        // Mengambil filter dari request
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Mengambil data barang masuk sesuai rentang tanggal yang dipilih
        $barangKeluar = DetailPengeluaranBarang::with('barang')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc') 
            ->get();

        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangKeluar' => $barangKeluar,
        ];

        return view('laporan.laporan-barang-keluar', $data);
    }

    public function downloadBarangKeluarPdf(Request $request)
    {
        // Mengambil filter dari request
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Mengambil data barang masuk sesuai rentang tanggal yang dipilih
        $barangKeluar = DetailPengeluaranBarang::with('barang')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc') 
            ->get();

        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangKeluar' => $barangKeluar,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-barang-keluar-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-barang-keluar-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }


    public function getBarangList($barangMasuk, $barangKeluar)
    {
        // Ensure that $barangMasuk and $barangKeluar are collections
        $barangMasuk = collect($barangMasuk);
        $barangKeluar = collect($barangKeluar);

        return Barang::all()->map(function ($barang) use ($barangMasuk, $barangKeluar) {
            // Group barangMasuk and barangKeluar by barang_id
            $barangMasukFiltered = $barangMasuk->where('barang_id', $barang->id);
            $barangKeluarFiltered = $barangKeluar->where('barang_id', $barang->id);

            $jumlahMasuk = $barangMasukFiltered->sum('jumlah_diterima');
            $jumlahKeluar = $barangKeluarFiltered->sum('jumlah_keluar');

            if ($jumlahMasuk > 0 || $jumlahKeluar > 0) {
                return [
                    'nama_barang' => $barang->nama_barang,
                    'satuan_stok' => $barang->jenisBarang->satuan_stok ?? 'N/A',
                    'jumlah_keluar' => $jumlahKeluar ?: 0,
                    'jumlah_masuk' => $jumlahMasuk ?: 0,
                ];
            }

            return null;
        })->filter(); // Filter null values from the result
    }

    public function showPerubahanPersediaan(Request $request)
    {
       // Mengambil filter dari request
       $filter = $request->get('filter', 'current_month');

       // Mendapatkan rentang tanggal berdasarkan filter
       $dates = $this->getDateRange($request, $filter);
       $startDate = $dates['startDate'];
       $endDate = $dates['endDate'];

        // Query the data for both incoming and outgoing goods
        $detailPenerimaan = DetailPenerimaanBarang::with([
            'PenerimaanBarang.jenisPenerimaanBarang',
            'PenerimaanBarang.user',
            'PenerimaanBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc') 
        ->get();

        $detailPengeluaran = DetailPengeluaranBarang::with([
            'PengeluaranBarang.jenisPengeluaranBarang',
            'PengeluaranBarang.user',
            'PengeluaranBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc') 
        ->get();

        // Return to the view
        return view('laporan.laporan-perubahan-persediaan', compact('detailPenerimaan', 'detailPengeluaran', 'filter'));
    }

    public function downloadPerubahanPersediaanPdf(Request $request)
    {
        // Mengambil filter dari request
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Mengambil data barang masuk sesuai rentang tanggal yang dipilih
        // Query the data for both incoming and outgoing goods
        $detailPenerimaan = DetailPenerimaanBarang::with([
            'PenerimaanBarang.jenisPenerimaanBarang',
            'PenerimaanBarang.user',
            'PenerimaanBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

        $detailPengeluaran = DetailPengeluaranBarang::with([
            'PengeluaranBarang.jenisPengeluaranBarang',
            'PengeluaranBarang.user',
            'PengeluaranBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();


        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'detailPenerimaan' => $detailPenerimaan,
            'detailPengeluaran' => $detailPengeluaran,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-perubahan-persediaan-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-barang-keluar-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }


    public function showBarangStokMinimal(Request $request)
    {
        $perPage = $request->input('perPage', 25); 

        $barangStokMinimal = Barang::with('jenisBarang')
            ->where('stok', '<=', 10) 
            ->paginate($perPage)
            ->appends(request()->except('page'));

        return view('laporan.laporan-stok-minimum', compact('barangStokMinimal'));
    }

    public function downloadBarangStokMinimalPdf(Request $request)
    {
        $barangStokMinimal = Barang::with('jenisBarang')
            ->where('stok', '<=', 10) // Menyaring barang yang stoknya kurang dari atau sama dengan 10
            ->get(); // Mengambil semua data tanpa pagination

        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'user' => Auth::user()->name,
            'barangStokMinimal' => $barangStokMinimal
        ];

        // Generate PDF dengan view 'laporan-stok-minimum-pdf'
        $pdf = PDF::loadView('laporan.laporan-stok-minimum-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-stok-minimum-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function showKadaluarsa(Request $request)
    {
        $perPage = $request->input('perPage', 25); 
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', Carbon::now()->addDays(30)) // Example filter for approaching expiry
            ->paginate($perPage)
            ->appends(request()->except('page'));
        return view('laporan.laporan-mendekati-kadaluarsa', compact('barangKadaluarsaMendekati'));
    }

    public function downloadKadaluarsaPdf(Request $request)
    {
    
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', Carbon::now()->addDays(30)) // Example filter for approaching expiry
            ->get(); // Mengambil semua data tanpa pagination

        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'user' => Auth::user()->name,
            'barangKadaluarsaMendekati' => $barangKadaluarsaMendekati
        ];

        // Generate PDF dengan view 'laporan-stok-minimum-pdf'
        $pdf = PDF::loadView('laporan.laporan-mendekati-kadaluarsa-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-mendekati-kadaluarsa-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function showTotalStok(Request $request)
    {
        // Get the perPage parameter from the request, default to 25
        $perPage = $request->input('perPage', 25);
    
        // Paginate the data based on the perPage value
        $allBarangs = Barang::with('jenisBarang') // Assuming "jenisBarang" is a relation on Barang model
            ->paginate($perPage)
            ->appends(request()->except('page'));
        // Get the total stock of all items
        $totalStokSemuaBarang = Barang::sum('stok'); // Sum of the 'stok' field from all barang
    
        return view('laporan.laporan-total-stok', compact('allBarangs', 'totalStokSemuaBarang'));
    }

    public function downloadTotalStokPdf(Request $request)
    {
        // Mendapatkan nilai perPage dari request, default 25 jika tidak ada
        $perPage = $request->input('perPage', 25);

        // Mengambil data barang sesuai dengan jumlah per halaman
        $allBarangs = Barang::with('jenisBarang')
            ->paginate($perPage)
            ->appends(request()->except('page', 'perPage')); // Menjaga parameter lainnya

        // Mendapatkan total stok seluruh barang
        $totalStokSemuaBarang = Barang::sum('stok');

        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'user' => Auth::user()->name,
            'allBarangs' => $allBarangs,
            'totalStokSemuaBarang' => $totalStokSemuaBarang,
        ];

        // Generate PDF dengan view 'laporan-total-stok-pdf'
        $pdf = PDF::loadView('laporan.laporan-total-stok-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-total-stok-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }



    public function showSaldo($type, Request $request)
    {
        // Mengambil filter dari request
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];
        $startYear = $dates['startYear'];
        $endYear = $dates['endYear'];
        $startMonth = $dates['startMonth'];
        $endMonth = $dates['endMonth'];
        

        // If the filter is based on a date range (e.g., 'last_30_days'), use that range
        if (isset($startDate) && isset($endDate)) {
            $allSaldoAwals = SaldoAwal::with('barang')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
        } else {
            // Otherwise, filter by year and month
            $allSaldoAwals = SaldoAwal::with('barang')
                ->where('tahun', '>=', $startYear)
                ->where('tahun', '<=', $endYear)
                ->where('bulan', '>=', $startMonth)
                ->where('bulan', '<=', $endMonth)
                ->get();
        }
        $totalSaldoAwal = $allSaldoAwals->sum('saldo_awal');
        $totalSaldoTerima = $allSaldoAwals->sum('total_terima');
        $totalSaldoKeluar = $allSaldoAwals->sum('total_keluar');
    
        // Mengirim data ke view
        return view('laporan.laporan-saldo-awal', 
                    compact('allSaldoAwals', 'type', 'filter', 'totalSaldoAwal', 
                            'totalSaldoTerima', 'totalSaldoKeluar'));
    }
    
    public function downloadSaldoAwalPdf($type, Request $request)
    {
        $filter = $request->get('filter', 'current_month');

        // Mendapatkan rentang tanggal berdasarkan filter
        $dates = $this->getDateRange($request, $filter);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];
        $startYear = $dates['startYear'];
        $endYear = $dates['endYear'];
        $startMonth = $dates['startMonth'];
        $endMonth = $dates['endMonth'];

        // If the filter is based on a date range (e.g., 'last_30_days'), use that range
        if (isset($startDate) && isset($endDate)) {
            $allSaldoAwals = SaldoAwal::with('barang')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
        } else {
            // Otherwise, filter by year and month
            $allSaldoAwals = SaldoAwal::with('barang')
                ->where('tahun', '>=', $startYear)
                ->where('tahun', '<=', $endYear)
                ->where('bulan', '>=', $startMonth)
                ->where('bulan', '<=', $endMonth)
                ->get();
        }

        // Prepare additional data for the PDF
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
        ];

        // Load the view and pass all the data (merged compact variables with additional data)
        $pdf = PDF::loadView('laporan.laporan-saldo-awal-pdf', array_merge(compact('allSaldoAwals', 'type', 'filter'), $data));

        // Download the PDF with a specific name
        return $pdf->download('laporan_saldo_awal_' . $type . '.pdf');
    }


}