@extends('layouts.app')

@section('title', 'Laporan Saldo Bulan Ini')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h2>Laporan {{$type}}</h2>
        </div>
        
        <!-- Filter Form -->
        <div class="card-body">
            <form method="GET" action="{{ route('laporan-saldo', ['type' => $type]) }}">
                    <div class="form-row align-items-center mt-4 mb-4">
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
                            <!-- Tombol untuk Download PDF -->
                            <a href="{{ route('laporan-saldo-awal-pdf', ['type' => $type])  }}?filter={{ request('filter') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-danger">Download PDF</a>
                        </div>
                            {{-- <a href="{{ route('generateReport') }}?download_pdf=true" class="btn btn-success">Download PDF</a> --}}
            </div>
        </form>
    </div>

        <!-- Saldo Report Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>{{ $type }}</th>
                            <th>Tanggal Ditambah</th>
                            <th>Tanggal Diupdate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($allSaldoAwals && $allSaldoAwals->count() > 0)
                            @foreach ($allSaldoAwals as $saldo_awal)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $saldo_awal->barang->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $saldo_awal->tahun }}</td>
                                    <td>{{ $saldo_awal->bulan }}</td>
                                    <td style="text-align: right;">
                                        @if($type === 'saldo-awal')
                                            {{ $saldo_awal->saldo_awal, 0 }}
                                        @elseif($type === 'saldo-terima')
                                            {{ $saldo_awal->total_terima, 0 }}
                                        @elseif($type === 'saldo-keluar')
                                            {{$saldo_awal->total_keluar, 0,  }}
                                        @endif
                                    </td>
                                    <td>{{ $saldo_awal->created_at }}</td>
                                    <td>{{ $saldo_awal->updated_at }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">Data tidak ditemukan!</td>
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
