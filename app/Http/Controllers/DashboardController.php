<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\barang as ModelsBarang;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPengeluaranBarang;
use App\Models\JenisPenerimaan;
use App\Models\JenisPengeluaran;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Models\PenerimaanBarang;
use App\Models\PengeluaranBarang;
use App\Models\SaldoAwal;
use App\Models\supkonpro;
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
        $startMonth = null;
        $endMonth = null;
        $startYear = null;
        $endYear = null;
        // Handle custom dates filter
        if ($filter === 'custom_dates') {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $startYear = $startDate->year;
            $endYear = $endDate->year;
            $startMonth = $startDate->month;
            $endMonth = $endDate->month;
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

        return compact('startDate', 'endDate', 'thisYear', 'thisMonth', 'startMonth', 'endMonth', 'startYear', 'endYear');
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

        // Ambil filter tahun dan bulan dari request
        $tahun = $request->get('tahun');
        $bulan = $request->get('bulan');

        // Jika tahun dan bulan dipilih, filter berdasarkan tahun dan bulan
        $allSaldoAwals = SaldoAwal::with('barang')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->get();

        // // Mengambil data saldo awal
        // $allSaldoAwals = SaldoAwal::with('barang')
        //     ->whereBetween('created_at', [$startDate, $endDate])
        //     ->get();

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
        // Retrieve filter from the request
        $filter = $request->get('filter', 'current_month');
        $dates = $this->getDateRange($request, $filter);

        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Base query for DetailPenerimaanBarang with relationships
        $query = DetailPenerimaanBarang::with('barang', 'penerimaanBarang.supkonpro', 'penerimaanBarang.jenispenerimaanbarang');

        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereHas('penerimaanBarang', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->whereHas('penerimaanBarang.jenispenerimaanbarang', function ($q) use ($request) {
                $q->where('id', $request->transaction_type);
            });
        }

        // Filter by supkonpro
        if ($request->filled('supkonpro')) {
            $query->whereHas('penerimaanBarang.supkonpro', function ($q) use ($request) {
                $q->where('id', $request->supkonpro);
            });
        }

        // Filter by product
        if ($request->filled('product')) {
            $query->where('barang_id', $request->product);
        }

        // Execute the query to retrieve filtered data
        $barangMasuk = $query->orderBy('created_at', 'desc')->get();

        // Retrieve options for filters
        $transactionTypes = JenisPenerimaan::all();
        $supkonpro = SupKonPro::all();
        $products = Barang::all();

        // Prepare data for the view
        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangMasuk' => $barangMasuk,
            'transactionTypes' => $transactionTypes,
            'supkonpro' => $supkonpro,
            'products' => $products,
        ];

        return view('laporan.laporan-barang-masuk', $data);
    }

    public function downloadBarangMasukPdf(Request $request)
    {
        // Retrieve filter from the request
        $filter = $request->get('filter', 'current_month');
        $dates = $this->getDateRange($request, $filter);
    
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];
    
        // Base query for DetailPenerimaanBarang with relationships
        $query = DetailPenerimaanBarang::with('barang', 'penerimaanBarang.supkonpro', 'penerimaanBarang.jenispenerimaanbarang');
    
        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereHas('penerimaanBarang', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }
    
        // Filter by transaction type
        $selectedTransactionType = null;
        if ($request->filled('transaction_type')) {
            $query->whereHas('penerimaanBarang.jenispenerimaanbarang', function ($q) use ($request) {
                $q->where('id', $request->transaction_type);
            });
    
            // Get the selected transaction type
            $selectedTransactionType = JenisPenerimaan::find($request->transaction_type);
        }
    
        // Filter by supkonpro
        $selectedSupkonpro = null;
        if ($request->filled('supkonpro')) {
            $query->whereHas('penerimaanBarang.supkonpro', function ($q) use ($request) {
                $q->where('id', $request->supkonpro);
            });
    
            // Get the selected supkonpro
            $selectedSupkonpro = SupKonPro::find($request->supkonpro);
        }
    
        // Filter by product
        $selectedProduct = null;
        if ($request->filled('product')) {
            $query->where('barang_id', $request->product);
    
            // Get the selected product
            $selectedProduct = Barang::find($request->product);
        }
    
        // Execute the query to retrieve filtered data
        $barangMasuk = $query->orderBy('created_at', 'desc')->get();
    
        // Kalkulasi total jumlah diterima dan total harga invoice
        $totalJumlahDiterima = $barangMasuk->sum('jumlah_diterima');
        $totalJumlahDiterima = number_format($totalJumlahDiterima, 2, '.', ''); // Format dengan dua angka desimal
        
        $totalHargaInvoice = $barangMasuk->reduce(function ($carry, $item) {
            return $carry + ($item->penerimaanBarang->harga_invoice ?? 0);
        }, 0);

        // Tambahkan ke data view
        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangMasuk' => $barangMasuk,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedTransactionType' => $selectedTransactionType->jenis ?? '-',
            'selectedSupkonpro' => $selectedSupkonpro->nama ?? '-',
            'selectedProduct' => $selectedProduct->nama_barang ?? '-',
            'totalJumlahDiterima' => $totalJumlahDiterima,
            'totalHargaInvoice' => $totalHargaInvoice,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-barang-masuk-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-barang-masuk-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }    

    public function showBarangKeluar(Request $request)
    {
        // Retrieve filter from the request
        $filter = $request->get('filter', 'current_month');
        $dates = $this->getDateRange($request, $filter);

        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Base query for DetailPenerimaanBarang with relationships
        $query = DetailPengeluaranBarang::with('barang', 'pengeluaranBarang.supkonpro', 
                 'pengeluaranBarang.jenispengeluaranbarang');

        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereHas('pengeluaranBarang', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        // Filter by transaction type
        if (!is_null($request->transaction_type) && $request->transaction_type !== '') {
            $query->whereHas('pengeluaranBarang.jenispengeluaranbarang', function ($q) use ($request) {
                $q->where('id', $request->transaction_type);
            });
        }
        
        // Filter by supkonpro
        if ($request->filled('supkonpro')) {
            $query->whereHas('pengeluaranBarang.supkonpro', function ($q) use ($request) {
                $q->where('id', $request->supkonpro);
            });
        }

        // Filter by product
        if ($request->filled('product')) {
            $query->where('barang_id', $request->product);
        }

        // Execute the query to retrieve filtered data
        $barangKeluar = $query->orderBy('created_at', 'desc')->get();

        // Retrieve options for filters
        $transactionTypes = JenisPengeluaran::all();
        $supkonpro = SupKonPro::all();
        $products = Barang::all();

        // Prepare data for the view
        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangKeluar' => $barangKeluar,
            'transactionTypes' => $transactionTypes,
            'supkonpro' => $supkonpro,
            'products' => $products,
        ];

        return view('laporan.laporan-barang-keluar', $data);
    }

    public function downloadBarangKeluarPdf(Request $request)
    {
        // Retrieve filter from the request
        $filter = $request->get('filter', 'current_month');
        $dates = $this->getDateRange($request, $filter);

        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        $query = DetailPengeluaranBarang::with('barang', 'pengeluaranBarang.supkonpro', 'pengeluaranBarang.jenispengeluaranbarang');

        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereHas('pengeluaranBarang', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        // Filter by transaction type
        $selectedTransactionType = null;
        if ($request->filled('transaction_type')) {
            $query->whereHas('pengeluaranBarang.jenispengeluaranbarang', function ($q) use ($request) {
                $q->where('id', $request->transaction_type);
            });

            // Get the selected transaction type
            $selectedTransactionType = JenisPengeluaran::find($request->transaction_type);
        }

        // Filter by supkonpro
        $selectedSupkonpro = null;
        if ($request->filled('supkonpro')) {
            $query->whereHas('pengeluaranBarang.supkonpro', function ($q) use ($request) {
                $q->where('id', $request->supkonpro);
            });

            // Get the selected supkonpro
            $selectedSupkonpro = SupKonPro::find($request->supkonpro);
        }

        // Filter by product
        $selectedProduct = null;
        if ($request->filled('product')) {
            $query->where('barang_id', $request->product);

            // Get the selected product
            $selectedProduct = Barang::find($request->product);
        }

        // Execute the query to retrieve filtered data
        $barangKeluar= $query->orderBy('created_at', 'desc')->get();

        $totalJumlahKeluar = $barangKeluar->sum('jumlah_keluar');
        $totalJumlahKeluar= number_format($totalJumlahKeluar, 2, '.', '');
        
        $totalHargaInvoice = $barangKeluar->reduce(function ($carry, $item) {
            return $carry + ($item->pengeluaranBarang->harga_invoice ?? 0);
        }, 0);

        // Tambahkan ke data view
        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'barangKeluar' => $barangKeluar,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedTransactionType' => $selectedTransactionType->jenis ?? '-',
            'selectedSupkonpro' => $selectedSupkonpro->nama ?? '-',
            'selectedProduct' => $selectedProduct->nama_barang ?? '-',
            'totalJumlahKeluar' => $totalJumlahKeluar,
            'totalHargaInvoice' => $totalHargaInvoice,
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

        // Query data Penerimaan (Barang Masuk)
        $detailPenerimaan = DetailPenerimaanBarang::with([
            'PenerimaanBarang.jenisPenerimaanBarang',
            'PenerimaanBarang.user',
            'PenerimaanBarang.supkonpro',
            'barang'
        ])
            ->whereHas('penerimaanBarang', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Query data Pengeluaran (Barang Keluar)
        $detailPengeluaran = DetailPengeluaranBarang::with([
            'PengeluaranBarang.jenisPengeluaranBarang',
            'PengeluaranBarang.user',
            'PengeluaranBarang.supkonpro',
            'barang'
        ])
        ->whereHas('pengeluaranBarang', function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // Gabungkan kedua data dan urutkan berdasarkan tanggal
        $allData = $detailPenerimaan->merge($detailPengeluaran)->sortByDesc('created_at');

        // Return ke view dengan data yang telah digabungkan
        return view('laporan.laporan-perubahan-persediaan', compact('allData', 'filter'));
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
        $detailPenerimaan = DetailPenerimaanBarang::with([
            'PenerimaanBarang.jenisPenerimaanBarang',
            'PenerimaanBarang.user',
            'PenerimaanBarang.supkonpro',
            'barang'
        ])
        ->whereHas('penerimaanBarang', function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // Mengambil data barang keluar sesuai rentang tanggal yang dipilih
        $detailPengeluaran = DetailPengeluaranBarang::with([
            'PengeluaranBarang.jenisPengeluaranBarang',
            'PengeluaranBarang.user',
            'PengeluaranBarang.supkonpro',
            'barang'
        ])
        ->whereHas('pengeluaranBarang', function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // Gabungkan data barang masuk dan keluar
        $allData = $detailPenerimaan->merge($detailPengeluaran)->sortByDesc('created_at');

        // Hitung total jumlah diterima, jumlah keluar, dan harga invoice
        $totalJumlahDiterima = $detailPenerimaan->sum('jumlah_diterima');
        $totalJumlahKeluar = $detailPengeluaran->sum('jumlah_keluar');
        $totalHargaInvoice = $allData->reduce(function ($carry, $item) {
            return $carry + ($item->PenerimaanBarang->harga_invoice ?? $item->PengeluaranBarang->harga_invoice ?? 0);
        }, 0);

        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'filter' => $filter,
            'user' => Auth::user()->name,
            'startDate' => $startDate->toFormattedDateString(),
            'endDate' => $endDate->toFormattedDateString(),
            'allData' => $allData,
            'totalJumlahDiterima' => $totalJumlahDiterima,
            'totalJumlahKeluar' => $totalJumlahKeluar,
            'totalHargaInvoice' => $totalHargaInvoice,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-perubahan-persediaan-pdf', $data);
        
        // Download PDF dengan nama file yang dinamis
        return $pdf->download('laporan-perubahan-persediaan-' . Carbon::now()->format('Y-m-d') . '.pdf');
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
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', Carbon::now()->addDays(60)) // Example filter for approaching expiry
            ->paginate($perPage)
            ->appends(request()->except('page'));
        return view('laporan.laporan-mendekati-kadaluarsa', compact('barangKadaluarsaMendekati'));
    }

    public function downloadKadaluarsaPdf(Request $request)
    {
    
        $barangKadaluarsaMendekati = Barang::where('kadaluarsa', '<=', Carbon::now()->addDays(60)) // Example filter for approaching expiry
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
        // Set batas memori dan waktu eksekusi
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', '300'); // 300 detik atau 5 menit

        // Mendapatkan total stok seluruh barang
        $totalStokSemuaBarang = Barang::sum('stok');

        // Mengambil seluruh data barang tanpa paginasi
        $allBarangs = Barang::with('jenisBarang')->get();

        // Siapkan data untuk laporan
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'user' => Auth::user()->name,
            'allBarangs' => $allBarangs,
            'totalStokSemuaBarang' => $totalStokSemuaBarang,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-total-stok-pdf', $data);
        
        // Unduh file PDF
        return $pdf->download('laporan-total-stok-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function showSaldo(Request $request) 
    {
        // Filter berdasarkan tahun, bulan, dan barang
        $tahun = $request->get('tahun');
        $bulan = $request->get('bulan');
        $barangId = $request->get('barang_id'); // Filter berdasarkan barang_id

        // Filter data berdasarkan input
        $query = SaldoAwal::with('barang');
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        if ($barangId) {
            $query->where('barang_id', $barangId);
        }

        $allSaldoAwals = $query->get();

        // Hitung total
        $totalSaldoAwal = $allSaldoAwals->sum('saldo_awal');
        $totalSaldoTerima = $allSaldoAwals->sum('total_terima');
        $totalSaldoKeluar = $allSaldoAwals->sum('total_keluar');

        // Ambil daftar barang untuk filter
        $barangs = Barang::all();

        // Mengirim data ke view
        return view('laporan.laporan-saldo-awal', compact(
            'allSaldoAwals', 'tahun', 'bulan', 'barangId', 
            'barangs', 'totalSaldoAwal', 'totalSaldoTerima', 'totalSaldoKeluar'
        ));
    }

    public function downloadSaldoAwalPdf(Request $request)
    {
        // Ambil filter dari request
        $tahun = $request->get('tahun');
        $bulan = $request->get('bulan');
        $barangId = $request->get('barang_id'); // Filter barang

        // Query data berdasarkan filter
        $query = SaldoAwal::with('barang');
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        if ($barangId) {
            $query->where('barang_id', $barangId);
        }

        $allSaldoAwals = $query->get();

        // Hitung total
        $totalSaldoAwal = $allSaldoAwals->sum('saldo_awal');
        $totalSaldoTerima = $allSaldoAwals->sum('total_terima');
        $totalSaldoKeluar = $allSaldoAwals->sum('total_keluar');
        $totalSaldoAkhir = $allSaldoAwals->sum('saldo_akhir');

        // Data tambahan untuk PDF
        $data = [
            'date' => Carbon::now()->toFormattedDateString(),
            'user' => Auth::user()->name,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'barangId' => $barangId,
            'totalSaldoAwal' => $totalSaldoAwal,
            'totalSaldoTerima' => $totalSaldoTerima,
            'totalSaldoKeluar' => $totalSaldoKeluar,
            'totalSaldoAkhir' => $totalSaldoAkhir,

        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.laporan-saldo-awal-pdf', array_merge(compact('allSaldoAwals', 'barangId', 'tahun', 'bulan'), $data));
        return $pdf->download('laporan_saldo_awal.pdf');
    }

}