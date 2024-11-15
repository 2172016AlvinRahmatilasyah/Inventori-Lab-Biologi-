<!-- resources/views/kelola-jenis-barang/detail-jenis-barang.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Jenis Barang')

@section('content')
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
                    <th>Tanggal Ditambah</th>
                    <th>Tanggal Diedit</th>
                    {{-- <th>Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->id }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->stok }}</td>
                        <td>{{ $barang->created_at }}</td>
                        <td>{{ $barang->updated_at }}</td>
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
