@extends('layouts.app')

@section('title', 'Barang Masuk Bulan Ini')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h2>Daftar Barang Masuk Bulan Ini</h2>
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
                                <th>Jenis Penerimaan</th>
                                <th>Nama Pengantar</th>
                                <th>Keterangan</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Diterima</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($detailPenerimaan) && count($detailPenerimaan) > 0)
                            @foreach ($detailPenerimaan as $penerimaan)
                                <tr>
                                    <td>{{ $penerimaan->id }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->id ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->user->name ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->jenispenerimaanbarang->jenis ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->nama_pengantar ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->barang->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->jumlah_diterima }}</td>
                                    <td>{{ number_format($penerimaan->harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ number_format($penerimaan->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->created_at }}</td>
                                    <td>{{ $penerimaan->updated_at }}</td>
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