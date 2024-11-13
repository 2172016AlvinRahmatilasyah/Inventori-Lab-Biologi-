@extends('layouts.app')

@section('title', 'Barang Keluar Bulan Ini')

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
                <h2>Daftar Barang Keluar Bulan Ini</h2>
            </div>     
        
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id detail</th>
                                <th>Id master</th>
                                <th>SupKonProy</th>
                                <th>Nama Staff</th>
                                <th>Jenis Pengeluaran</th>
                                <th>Nama Pengambil</th>
                                <th>Keterangan</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Keluar</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($detailPengeluaran) && count($detailPengeluaran) > 0)
                            @foreach ($detailPengeluaran as $pengeluaran)
                                <tr>
                                    <td>{{ $pengeluaran->id }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->id ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->user->name ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->jenispengeluaranbarang->jenis ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->nama_pengambil ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->keterangan ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->barang->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->jumlah_keluar }}</td>
                                    <td>{{ number_format($pengeluaran->harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ number_format($pengeluaran->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->created_at }}</td>
                                    <td>{{ $pengeluaran->updated_at }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="13">Data tidak ada!</td>
                            </tr>
                        @endif                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection