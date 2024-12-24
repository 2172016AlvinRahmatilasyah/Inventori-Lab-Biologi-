@extends('layouts.app')

@section('title', 'Detail Transaksi Barang')

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
            <h2>Detail Transaksi Barang: {{ $barang->nama_barang }}</h2>
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

        <div class="card-body">
            <h5>Detail Transaksi Barang</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
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
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Display Data for Penerimaan Barang --}}
                        @if(isset($all_master_penerimaans) && count($all_master_penerimaans) > 0)
                            @foreach ($all_master_penerimaans as $master_penerimaan)
                                @foreach ($master_penerimaan->detailpenerimaanbarang as $detail_penerimaan)
                                    <tr>
                                        <td>{{ $master_penerimaan->invoice }}</td>
                                        <td>{{ $master_penerimaan->tanggal }}</td>
                                        <td>{{ $master_penerimaan->supkonpro->nama ?? 'N/A' }}</td>
                                        <td>{{ $master_penerimaan->user->name }}</td>
                                        <td>Barang Masuk</td>
                                        <td>{{ $detail_penerimaan->barang->nama_barang ?? 'N/A' }}</td>
                                        <td style="text-align: right;">{{ $detail_penerimaan->jumlah_diterima }}</td>
                                        <td style="text-align: right;">-</td>
                                        <td style="text-align: right;">{{ number_format($detail_penerimaan->harga, 0, ',', '.') }}</td>
                                        <td style="text-align: right;">{{ number_format($detail_penerimaan->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif

                        {{-- Display Data for Pengeluaran Barang --}}
                        @if(isset($all_master_pengeluarans) && count($all_master_pengeluarans) > 0)
                            @foreach ($all_master_pengeluarans as $master_pengeluaran)
                                @foreach ($master_pengeluaran->detailpengeluaranbarang as $detail_pengeluaran)
                                    <tr>
                                        <td>{{ $master_pengeluaran->invoice }}</td>
                                        <td>{{ $master_pengeluaran->tanggal }}</td>
                                        <td>{{ $master_pengeluaran->supkonpro->nama ?? 'N/A' }}</td>
                                        <td>{{ $master_pengeluaran->user->name }}</td>
                                        <td>Barang Keluar</td>
                                        <td>{{ $detail_pengeluaran->barang->nama_barang ?? 'N/A' }}</td>
                                        <td style="text-align: right;">-</td>
                                        <td style="text-align: right;">{{ $detail_pengeluaran->jumlah_keluar }}</td>
                                        <td style="text-align: right;">{{ number_format($detail_pengeluaran->harga, 0, ',', '.') }}</td>
                                        <td style="text-align: right;">{{ number_format($detail_pengeluaran->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif

                        {{-- No Data Found --}}
                        @if((!isset($all_master_penerimaans) || count($all_master_penerimaans) == 0) && 
                            (!isset($all_master_pengeluarans) || count($all_master_pengeluarans) == 0))
                            <tr>
                                <td colspan="10" class="text-center">Data tidak ditemukan!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
