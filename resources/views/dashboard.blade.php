@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="{{ route('generateReport') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
            </a>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Laporan Barang Masuk -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-barang-masuk') }}" style="text-decoration: none;">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laporan Barang Masuk Bulan ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $barangMasukBulanIni }} Transaksi
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Laporan Barang Keluar -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-barang-keluar') }}" style="text-decoration: none;">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Laporan Barang Keluar bulan ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $barangKeluarBulanIni }} Transaksi
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Laporan Persediaan -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-perubahan-persediaan') }}" style="text-decoration: none;">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Laporan Perubahan Persediaan Bulan ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $barangMasukBulanIni + $barangKeluarBulanIni }} Transaksi
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <!-- Content Row -->
        <div class="row">
           <!-- Stok Mendekati/Sudah Minimum -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-stok-minimum') }}" style="text-decoration: none;">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stok mendekati/sudah minimum
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($barangStokMinimal) }} Barang/Bahan
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Laporan Stok Minimum dan Kadaluarsa -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Stok mendekati kadaluarsa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ count($barangKadaluarsaMendekati) }} Warnings
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Total Barang -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                            Total Stok Keseluruhan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalStok  }} Items
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Saldo Awal Keseluruhan Barang Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalSaldoAwalBulanIni, 0, ',', '.') }} 
                        </div>
                    </div>
                </div>
            </div>
            
            

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Saldo Terima Keseluruhan Barang Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalSaldoTerimaBulanIni, 0, ',', '.') }} 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Saldo Keluar Keseluruhan Barang Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalSaldoKeluarBulanIni, 0, ',', '.') }}  
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Data Permintaan Proyek -->
        {{-- <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Permintaan Proyek</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Proyek</th>
                                        <th>Jumlah Permintaan</th>
                                        <th>Status</th>
                                        <th>Tanggal Permintaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Proyek A</td>
                                        <td>50 Items</td>
                                        <td>Pending</td>
                                        <td>2024-10-27</td>
                                    </tr>
                                    <tr>
                                        <td>Proyek B</td>
                                        <td>30 Items</td>
                                        <td>Approved</td>
                                        <td>2024-10-25</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Chart Example Row -->
        {{-- <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Saldo Awal per Bulan Keseluruhan Barang Tahun Ini</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Stok Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="inventoryPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        
    </div>

    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-pie-demo.js') }}"></script>
@endsection
