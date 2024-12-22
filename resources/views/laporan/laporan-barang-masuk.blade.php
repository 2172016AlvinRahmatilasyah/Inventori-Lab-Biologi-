@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

{{-- <script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script> --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <h1>Laporan Barang Masuk</h1>

    <form method="get" action="{{ route('laporan-barang-masuk') }}">
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
                <a href="{{ route('laporan-barang-masuk-pdf') }}?filter={{ request('filter') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-danger">Download PDF</a>
            </div>
        </div>
    </form>

    <div class="mt-3">
        <div style="overflow-x: auto;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        {{-- <th>Id master</th>
                        <th>Id detail</th> --}}
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>SupKonProy</th>
                        <th>Nama Staff</th>
                        <th>Jenis Penerimaan</th>
                        <th>Nama Pengantar</th>
                        <th>Keterangan</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Diterima</th>
                        <th>Harga</th>
                        <th>Total Harga</th>
                        <th>Harga Invoice</th>
                        {{-- <th>Tanggal Ditambah</th>
                        <th>Tanggal Diupdate</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if(isset($barangMasuk) && count($barangMasuk) > 0)
                        @foreach ($barangMasuk as $item)
                            <tr>
                                {{-- <td>{{ $item->PenerimaanBarang->id ?? 'N/A' }}</td>
                                <td>{{ $item->id }}</td> --}}
                                <td>{{ $item->PenerimaanBarang->invoice }}</td>
                                <td>{{ $item->PenerimaanBarang->tanggal }}</td>
                                <td>{{ $item->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                                <td>{{ $item->PenerimaanBarang->user->name ?? 'N/A'}}</td>
                                <td>{{ $item->PenerimaanBarang->jenispenerimaanbarang->jenis  ?? 'N/A' }}</td>
                                <td>{{ $item->PenerimaanBarang->nama_pengantar ?? 'N/A'}}</td>
                                <td>{{ $item->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                                <td>{{ $item->barang->nama_barang ?? 'N/A'}}</td>
                                <td style="text-align: right;">{{ $item->jumlah_diterima }}</td>
                                <td style="text-align: right;">{{ number_format($item->harga, 0, ',', '.') ?? 'N/A'}}</td>
                                <td style="text-align: right;">{{ number_format($item->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                                <td style="text-align: right;">{{ number_format($item->penerimaanBarang->harga_invoice, 0, ',', '.') ?? 'N/A'}}</td>
                                {{-- <td>{{ $item->created_at }}</td>
                                <td>{{ $item->updated_at }}</td> --}}
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="14">Tidak Ada Transaksi Barang Masuk!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
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
