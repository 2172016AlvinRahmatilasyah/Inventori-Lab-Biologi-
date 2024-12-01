@extends('layouts.app')

@section('title', 'Saldo Awal')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h2>Data Saldo Awal</h2>
            <a href="/add-saldo-awal" class="btn btn-success btn-sm ml-auto">Tambah Saldo Awal</a>
        </div>
        
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        @if(Session::has('fail'))
            <div class="alert alert-danger">
                {{ Session::get('fail') }}
            </div>
        @endif

        <div class="mb-3">
            <form action="{{ route('saldoawals.search') }}" method="GET" class="d-flex mt-3">
                <input type="text" name="query" class="form-control w-50 ml-3" placeholder="Search here" value="{{ request()->get('query') }}">
                <button type="submit" class="btn btn-primary ml-2">Search</button>
                <a href="{{ route('saldo-awal') }}" class="btn btn-secondary ml-3 ">Reset</a>
            </form>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Saldo Awal</th>
                            <th>Total Terima</th>
                            <th>Total Keluar</th>
                            <th>Saldo Akhir</th>
                            <th>Tanggal Ditambah</th>
                            <th>Tanggal Diupdate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($all_saldo_awals) && count($all_saldo_awals) > 0)
                            @php
                                $previousBarangId = null;
                            @endphp
                            @foreach ($all_saldo_awals as $saldo_awal)
                                @if($previousBarangId !== $saldo_awal->barang_id)
                                    <!-- Insert empty row to break when barang_id changes -->
                                    @if($previousBarangId !== null)
                                        <tr><td colspan="10" class="text-center">-----</td></tr>
                                    @endif
                                    <tr>
                                        <td colspan="10" class="text-center"><strong>Barang: {{ $saldo_awal->barang->nama_barang ?? 'N/A' }}</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $saldo_awal->barang->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $saldo_awal->tahun }}</td>
                                    <td>{{ $saldo_awal->bulan }}</td>
                                    <td>{{ number_format($saldo_awal->saldo_awal, 0, ',', '.') }}</td>
                                    <td>{{ number_format($saldo_awal->total_terima, 0, ',', '.') }}</td>
                                    <td>{{ number_format($saldo_awal->total_keluar, 0, ',', '.') }}</td>
                                    <td>{{ number_format($saldo_awal->saldo_akhir, 0, ',', '.') }}</td>
                                    <td>{{ $saldo_awal->created_at }}</td>
                                    <td>{{ $saldo_awal->updated_at }}</td>
                                </tr>
                                @php
                                    $previousBarangId = $saldo_awal->barang_id;
                                @endphp
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10">Data tidak ditemukan!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
