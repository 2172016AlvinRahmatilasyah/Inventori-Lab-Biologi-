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
        // Mengambil tanggal saat ini
        $startDate = null;
        $endDate = null;
        $thisYear = Carbon::now()->year;
        $thisMonth = Carbon::now()->month;

        // Inisialisasi tanggal mulai dan akhir berdasarkan filter
        switch ($filter) {
            case 'current_month':
                // Show the current month
                $startMonth = $thisMonth;
                $endMonth = $thisMonth;
                $startYear = $thisYear;
                $endYear = $thisYear;
                break;

            case 'current_year':
                // Show the current year
                $startMonth = 1;  // January
                $endMonth = 12;   // December
                $startYear = $thisYear;
                $endYear = $thisYear;
                break;

            case 'last_30_days':
                // For the last 30 days (not dependent on year/month)
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();
                break;

            case 'last_60_days':
                // For the last 60 days
                $startDate = Carbon::now()->subDays(60);
                $endDate = Carbon::now();
                break;

            case 'last_90_days':
                // For the last 90 days
                $startDate = Carbon::now()->subDays(90);
                $endDate = Carbon::now();
                break;

            case 'previous_month':
                // Show the previous month
                $startMonth = Carbon::now()->subMonth()->month;
                $endMonth = $startMonth;
                $startYear = Carbon::now()->subMonth()->year;
                $endYear = $startYear;
                break;

            case 'previous_year':
                // Show the previous year
                $startMonth = 1; // January
                $endMonth = 12;  // December
                $startYear = Carbon::now()->subYear()->year;
                $endYear = $startYear;
                break;

            default:
                // Default filter - current month
                $startMonth = $thisMonth;
                $endMonth = $thisMonth;
                $startYear = $thisYear;
                $endYear = $thisYear;
                break;
        }

        // Mengembalikan hasil
        return compact('startDate', 'endDate', 'thisYear', 'thisMonth', 'startMonth',
                        'endMonth', 'startYear', 'endYear'
                      );
    }

    public function index(Request $request)
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

        // Fetch data based on the selected filter
        $barangMasuk = PenerimaanBarang::whereBetween('created_at', [$startDate, $endDate])->count();
        $barangKeluar = PengeluaranBarang::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalPerubahanPersediaan = $barangMasuk + $barangKeluar;
        $barangStokMinimal = Barang::where('stok', '<=', 20)->get();
        $twoMonthsLater = Carbon::now()->addMonths(2);
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', $twoMonthsLater)->get();
        $totalStok = Barang::sum('stok');
        
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

        // Prepare data for the report
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
            //  'all_saldo_awals' => $saldoAwal,
             'totalPerubahanPersediaan'=>$totalPerubahanPersediaan
        ];

        // Otherwise, return the view
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
        ->get();

        $detailPengeluaran = DetailPengeluaranBarang::with([
            'PengeluaranBarang.jenisPengeluaranBarang',
            'PengeluaranBarang.user',
            'PengeluaranBarang.supkonpro',
            'barang'
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
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
        // Get the page size from the request, default to 25 if not provided
        $perPage = $request->input('perPage', 25); 
    
        // Query with pagination
        $barangStokMinimal = Barang::with('jenisBarang')
            ->where('stok', '<=', 10) // Example condition, adjust as needed
            ->paginate($perPage); // Paginate results
    
        return view('laporan.laporan-stok-minimum', compact('barangStokMinimal'));
    }
    

    public function showKadaluarsa(Request $request)
    {
        $perPage = $request->input('perPage', 25); // Default to 25 if not specified
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', Carbon::now()->addDays(30)) // Example filter for approaching expiry
            ->paginate($perPage);

        return view('laporan.laporan-mendekati-kadaluarsa', compact('barangKadaluarsaMendekati'));
    }


    public function showTotalStok(Request $request)
    {
        // Get the perPage parameter from the request, default to 25
        $perPage = $request->input('perPage', 25);
    
        // Paginate the data based on the perPage value
        $allBarangs = Barang::with('jenisBarang') // Assuming "jenisBarang" is a relation on Barang model
            ->paginate($perPage);
    
        // Get the total stock of all items
        $totalStokSemuaBarang = Barang::sum('stok'); // Sum of the 'stok' field from all barang
    
        return view('laporan.laporan-total-stok', compact('allBarangs', 'totalStokSemuaBarang'));
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