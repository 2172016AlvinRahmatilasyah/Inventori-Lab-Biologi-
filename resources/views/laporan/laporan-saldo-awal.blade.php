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
                <div class="form-group">
                    <label for="filter">Pilih Filter</label>
                    <select name="filter" id="filter" class="form-control">
                        <option value="current_month" {{ request('filter') == 'current_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="current_year" {{ request('filter') == 'current_year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="last_30_days" {{ request('filter') == 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="last_60_days" {{ request('filter') == 'last_60_days' ? 'selected' : '' }}>60 Hari Terakhir</option>
                        <option value="last_90_days" {{ request('filter') == 'last_90_days' ? 'selected' : '' }}>90 Hari Terakhir</option>
                        <option value="previous_month" {{ request('filter') == 'previous_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="previous_year" {{ request('filter') == 'previous_year' ? 'selected' : '' }}>Tahun Lalu</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
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
                                    <td>
                                        @if($type === 'saldo-awal')
                                            {{ number_format($saldo_awal->saldo_awal, 0, ',', '.') }}
                                        @elseif($type === 'saldo-terima')
                                            {{ number_format($saldo_awal->total_terima, 0, ',', '.') }}
                                        @elseif($type === 'saldo-keluar')
                                            {{ number_format($saldo_awal->total_keluar, 0, ',', '.') }}
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
    {{-- Download PDF --}}
    <form method="get" action="{{ route('laporan-saldo-awal-pdf', ['type' => $type]) }}">
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <button type="submit" name="download_pdf" class="btn btn-danger">Download PDF</button>
    </form>
    
</div>
@endsection
