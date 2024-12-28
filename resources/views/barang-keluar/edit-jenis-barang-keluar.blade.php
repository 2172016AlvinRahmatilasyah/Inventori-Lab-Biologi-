@extends('layouts.app')

@section('title', 'Edit Jenis Barang Keluar')

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
            <div class="card-header text-center">Edit Jenis Barang Keluar</div>
            @if (Session::has('fail'))
                <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
            @endif
            <div class="card-body">
                <form action="{{ route('EditJenisBarangKeluar') }}" method="post">
                    @csrf
                    @method('PUT') 
                    <input type="hidden" name="jenis_id" value="{{ $JenisPengeluaran->id }}">

                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Barang Keluar</label>
                        <input type="text" name="jenis" value="{{ $JenisPengeluaran->jenis }}" 
                            class="form-control" id="formGroupExampleInput" 
                            placeholder="Enter jenis">
                        @error('jenis')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                
                    <button type="submit" class="btn btn-primary w-100 mb-2">Save</button>
                    <button type="button" class="btn btn-danger w-100" onclick="history.back()">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endsection
