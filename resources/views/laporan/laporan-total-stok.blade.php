@extends('layouts.app')

@section('title', 'Total Stok Keseluruhan')

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
                <h2>Total Stok Keseluruhan</h2>
            </div>     
        
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nama Barang</th>
                                <th>Jenis Barang</th>
                                <th>Stok</th>
                                <th>Satuan Stok</th>
                                <th>Kadaluarsa</th>
                                <th>Lokasi</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($allBarangs->count() > 0)
                                @foreach ($allBarangs as $barang)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td>{{ $barang->jenisBarang->nama_jenis_barang ?? 'N/A' }}</td>
                                        <td>{{ $barang->stok }}</td>
                                        <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                                        <td>{{ $barang->kadaluarsa }}</td>
                                        <td>{{ $barang->lokasi }}</td>
                                        <td>{{ $barang->created_at }}</td>
                                        <td>{{ $barang->updated_at }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3"><strong>Total Stok Seluruh Barang</strong></td>
                                    <td colspan="6"><strong>{{ $totalStokSemuaBarang }}</strong></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="9">Barang tidak ditemukan!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection