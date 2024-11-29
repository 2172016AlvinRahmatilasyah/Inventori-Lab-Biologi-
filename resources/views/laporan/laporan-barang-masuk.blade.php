{{-- resources/views/laporan/laporan-barang-masuk.blade.php --}}

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
        <div class="form-group">
            <label for="filter">Pilih Filter</label>
            <select name="filter" id="filter" class="form-control">
                <option value="current_month" {{ request('filter') == 'current_month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="current_year" {{ request('filter') == 'current_year' ? 'selected' : '' }}>Tahun Ini</option>
                <option value="last_30_days" {{ request('filter') == 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                <option value="last_60_days" {{ request('filter') == 'last_60_days' ? 'selected' : '' }}>60 Hari Terakhir</option>
                <option value="last_90_days" {{ request('filter') == 'last_90_days' ? 'selected' : '' }}>90 Hari Terakhir</option>
                <option value="last_12_months" {{ request('filter') == 'last_12_months' ? 'selected' : '' }}>12 Bulan Terakhir</option>
                <option value="month_to_date" {{ request('filter') == 'month_to_date' ? 'selected' : '' }}>Bulan Ini Sampai Tanggal</option>
                <option value="previous_month" {{ request('filter') == 'previous_month' ? 'selected' : '' }}>Bulan Lalu</option>
                <option value="previous_year" {{ request('filter') == 'previous_year' ? 'selected' : '' }}>Tahun Lalu</option>
                <option value="year_to_date" {{ request('filter') == 'year_to_date' ? 'selected' : '' }}>Tahun Ini Sampai Tanggal</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
    </form>

    <div class="mt-3">
        <h3>Data Barang Masuk</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id detail</th>
                    <th>Id master</th>
                    <th>SupKonProy</th>
                    <th>Nama Staff</th>
                    <th>Jenis Penerimaan</th>
                    <th>Nama Pengantar</th>
                    <th>Keterangan</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Diterima</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                    {{-- <th>Tanggal Ditambah</th>
                    <th>Tanggal Diupdate</th> --}}
                </tr>
            </thead>
            <tbody>
                @if(isset($barangMasuk) && count($barangMasuk) > 0)
                @foreach ($barangMasuk as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->PenerimaanBarang->id ?? 'N/A' }}</td>
                            <td>{{ $item->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                            <td>{{ $item->PenerimaanBarang->user->name ?? 'N/A'}}</td>
                            <td>{{ $item->PenerimaanBarang->jenispenerimaanbarang->jenis  ?? 'N/A' }}</td>
                            <td>{{ $item->PenerimaanBarang->nama_pengantar ?? 'N/A'}}</td>
                            <td>{{ $item->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                            <td>{{ $item->barang->nama_barang ?? 'N/A'}}</td>
                            <td>{{ $item->jumlah_diterima }}</td>
                            <td>{{ number_format($item->harga, 0, ',', '.') ?? 'N/A'}}</td>
                            <td>{{ number_format($item->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                            {{-- <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td> --}}
                        </tr>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13">Tidak Ada Transaksi Barang Masuk!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Download PDF --}}
    <form method="get" action="{{ route('laporan-barang-masuk-pdf') }}">
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <button type="submit" name="download_pdf" class="btn btn-danger">Download PDF</button>
    </form>    
    
    </div>

@endsection