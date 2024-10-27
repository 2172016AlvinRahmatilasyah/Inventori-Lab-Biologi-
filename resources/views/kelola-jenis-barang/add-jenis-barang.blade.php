@extends('layouts.app')

@section('title', 'Tambah Jenis Barang')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <div class="container">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header text-center">Tambah Jenis Barang</div>
            @if (Session::has('fail'))
                <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
            @endif
            <div class="card-body">
                <form action="{{ route('AddJenisBarang') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_jenis_barang" class="form-label">Nama Jenis Barang</label>
                        <input type="text" name="nama_jenis_barang" id="nama_jenis_barang" value="{{ old('nama_jenis_barang') }}" class="form-control" placeholder="Enter Nama jenis barang">
                        @error('nama_jenis_barang')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="satuan_stok" class="form-label">Satuan Stok</label>
                        <input type="text" name="satuan_stok" id="satuan_stok" class="form-control" value="{{ old('satuan_stok') }}" placeholder="Enter Nama jenis barang">
                        @error('satuan_stok')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
