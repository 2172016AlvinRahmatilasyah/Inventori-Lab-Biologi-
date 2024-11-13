@extends('layouts.app')

@section('title', 'Barang Stok Mendekati/Sudah Minimum')

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
            <h2>Daftar Stok Mendekati/Sudah Minimum</h2>
        </div>     

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Satuan Stok Barang</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($barangStokMinimal) > 0)
                            @foreach($barangStokMinimal as $barang)
                                <tr>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                                    <td>{{ $barang->stok }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">Tidak Ada Stok Mendekati/Sudah Minimum!</td> 
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
