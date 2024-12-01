@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Include Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Include Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            {{-- <a href="{{ route('generateReport') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
            </a> --}}
        </div>
        <form method="GET" class="mb-4">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <select name="filter" class="form-control" id="filter">
                        <option value="current_month" {{ request('filter') == 'current_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="current_year" {{ request('filter') == 'current_year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="last_30_days" {{ request('filter') == 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="last_60_days" {{ request('filter') == 'last_60_days' ? 'selected' : '' }}>60 Hari Terakhir</option>
                        <option value="last_90_days" {{ request('filter') == 'last_90_days' ? 'selected' : '' }}>90 Hari Terakhir</option>
                        <option value="last_12_months" {{ request('filter') == 'last_12_months' ? 'selected' : '' }}>12 Bulan Terakhir</option>
                        <option value="month_to_date" {{ request('filter') == 'month_to_date' ? 'selected' : '' }}>Awal Bulan Ini Hingga Tanggal Saat Ini</option>
                        <option value="previous_month" {{ request('filter') == 'previous_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="previous_year" {{ request('filter') == 'previous_year' ? 'selected' : '' }}>Tahun Lalu</option>
                        <option value="year_to_date" {{ request('filter') == 'year_to_date' ? 'selected' : '' }}>Tahun Ini Sampai Tanggal Saat Ini</option>
                        <option value="custom_dates" {{ request('filter') == 'custom_dates' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="date" name="start_date" class="form-control" placeholder="Start Date" value="{{ request('start_date') }}" id="start_date" {{ request('filter') == 'custom_dates' ? '' : 'disabled' }}>
                </div>
                <div class="col-auto">
                    <input type="date" name="end_date" class="form-control" placeholder="End Date" value="{{ request('end_date') }}" id="end_date" {{ request('filter') == 'custom_dates' ? '' : 'disabled' }}>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        
        <!-- Content Row -->
        <div class="row">
            <!-- Laporan Barang Masuk -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-barang-masuk') }}" style="text-decoration: none;">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laporan Barang Masuk
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $barangMasuk }} Transaksi
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Laporan Barang Keluar -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-barang-keluar') }}" style="text-decoration: none;">
                    <div class="card border-left-dark shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Laporan Barang Keluar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $barangKeluar }} Transaksi
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Laporan Perubahan Persediaan -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-perubahan-persediaan') }}" style="text-decoration: none;">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Laporan Perubahan Persediaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalPerubahanPersediaan }} Transaksi
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Saldo Awal -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ url('/laporan-saldo/saldo-awal') }}" style="text-decoration: none;">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Saldo Awal Keseluruhan Barang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalSaldoAwal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Saldo Terima -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ url('/laporan-saldo/saldo-terima') }}" style="text-decoration: none;">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Saldo Terima Keseluruhan Barang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalSaldoTerima, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Saldo Keluar -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ url('/laporan-saldo/saldo-keluar') }}" style="text-decoration: none;">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Saldo Keluar Keseluruhan Barang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalSaldoKeluar, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <h1 class="h3 mb-2 text-gray-800">Informasi</h1>
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
                                {{ count($barangStokMinimal) }} 
                                Barang/Bahan
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Laporan Stok Minimum dan Kadaluarsa -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-mendekati-kadaluarsa') }}" style="text-decoration: none;">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Stok mendekati kadaluarsa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($barangKadaluarsaMendekati) }} 
                                Warnings
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Statistik Total Barang -->
            <div class="col-xl-4 col-md-6 mb-4">
                <a href="{{ route('laporan-total-stok') }}" style="text-decoration: none;">
                    <div class="card border-left-dark shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Total Stok Keseluruhan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalStok }} 
                                Items
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
                const filterSelect = document.getElementById('filter');
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                // Function to toggle date inputs based on the selected filter
                function toggleDateInputs() {
                    if (filterSelect.value === 'custom_dates') {
                        startDateInput.disabled = false;
                        endDateInput.disabled = false;
                    } else {
                        startDateInput.disabled = true;
                        endDateInput.disabled = true;
                    }
                }

                // Initialize the date inputs based on the current selected filter
                toggleDateInputs();

                // Add event listener for filter changes
                filterSelect.addEventListener('change', toggleDateInputs);
        });
    </script>
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-pie-demo.js') }}"></script>
@endsection
