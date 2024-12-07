@extends('layouts.app')

@section('title', 'Laporan Perubahan Persediaan')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h2>Laporan Perubahan Persediaan</h2>
        </div>

        <!-- Filter Form -->
        <div class="card-body">
            <form method="get" action="{{ route('laporan-perubahan-persediaan') }}">
                <!-- Filter Inputs -->
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
                    <div class="col-auto">
                        <a href="{{ route('laporan-perubahan-persediaan-pdf') }}?filter={{ request('filter') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-danger">Download PDF</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table for Combined Barang Masuk and Barang Keluar -->
        <div class="card-body">
            <h5>Laporan Transaksi Barang</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Id Master</th>
                            <th>Id Detail</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>SupKonProy</th>
                            <th>Nama Staff</th>
                            <th>Jenis Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Masuk</th>
                            <th>Jumlah Keluar</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                            <th>Harga Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($allData) && count($allData) > 0)
                            @foreach ($allData as $data)
                                <tr>
                                    <td>{{ $data->PenerimaanBarang->id ?? $data->PengeluaranBarang->id ?? 'N/A' }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->PenerimaanBarang->invoice ?? $data->PengeluaranBarang->invoice ?? 'N/A' }}</td>
                                    <td>{{ $data->PenerimaanBarang->tanggal ?? $data->PengeluaranBarang->tanggal ?? 'N/A' }}</td>
                                    <td>{{ $data->PenerimaanBarang->supkonpro->nama ?? $data->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                                    <td>{{ $data->PenerimaanBarang->user->name ?? $data->PengeluaranBarang->user->name ?? 'N/A' }}</td>
                                    <td>{{ $data->PenerimaanBarang->jenispenerimaanbarang->jenis ?? $data->PengeluaranBarang->jenispengeluaranbarang->jenis ?? 'N/A' }}</td>
                                    <td>{{ $data->barang->nama_barang ?? 'N/A' }}</td>
                                    <td style="text-align: right;">{{ $data->jumlah_diterima ?? 0 }}</td>
                                    <td style="text-align: right;">{{ $data->jumlah_keluar ?? 0 }}</td>
                                    <td style="text-align: right;">{{ number_format($data->harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td style="text-align: right;">{{ number_format($data->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td class="text-right">{{ number_format( $data->PenerimaanBarang->harga_invoice ?? $data->PengeluaranBarang->harga_invoice ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">Data tidak ada!</td>
                            </tr>
                        @endif                        
                    </tbody>
                </table>
            </div>
        </div>
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
@endsection
