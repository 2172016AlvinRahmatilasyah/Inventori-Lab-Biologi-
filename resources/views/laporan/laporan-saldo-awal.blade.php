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
                <div class="form-row align-items-center">
                    <!-- Filter Tahun -->
                    <div class="col-auto">
                        <select name="tahun" class="form-control" id="tahun">
                            <option value="">Pilih Tahun</option>
                            @for ($i = 2020; $i <= now()->year+1; $i++)
                                <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
            
                    <!-- Filter Bulan -->
                    <div class="col-auto">
                        <select name="bulan" class="form-control" id="bulan">
                            <option value="">Pilih Bulan</option>
                            <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                            <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                            <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                            <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                            <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                            <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                            <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                            <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                            <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>
            
                    <!-- Tombol Filter -->
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
            
                    <!-- Tombol Download PDF -->
                    <div class="col-auto">
                        <a href="{{ route('laporan-saldo-awal-pdf', ['type' => $type]) }}?tahun={{ request('tahun') }}&bulan={{ request('bulan') }}" class="btn btn-danger">Download PDF</a>
                    </div>
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
                            {{-- <th>Tanggal Ditambah</th>
                            <th>Tanggal Diupdate</th> --}}
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
                                    {{-- <td>{{ $saldo_awal->created_at }}</td>
                                    <td>{{ $saldo_awal->updated_at }}</td> --}}
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">Data tidak ditemukan!</td>
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
