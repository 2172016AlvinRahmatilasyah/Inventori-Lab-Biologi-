<!-- resources/views/kelola-jenis-barang/detail-jenis-barang.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Jenis Barang')

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
<div class="container-fluid">

    <h2>Detail Jenis Barang: {{ $jenis_barang->nama_jenis_barang }}</h2>

    <p><strong>Satuan Stok:</strong> {{ $jenis_barang->satuan_stok }}</p>
    <br>
    <h3>Daftar Barang:</h3>
    @if($barangs->isEmpty())
        <p>Tidak ada barang terkait untuk jenis barang ini.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Stok</th>
                    {{-- <th>Tanggal Ditambah</th>
                    <th>Tanggal Diedit</th> --}}
                    {{-- <th>Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->id }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->stok }}</td>
                        {{-- <td>{{ $barang->created_at }}</td>
                        <td>{{ $barang->updated_at }}</td> --}}
                        {{-- <td>
                            <a href="/edit-barang/{{ $barang->id }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="/delete-barang/{{ $barang->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('kelola-jenis-barang') }}" class="btn btn-secondary">Back to List</a>

</div>
@endsection
