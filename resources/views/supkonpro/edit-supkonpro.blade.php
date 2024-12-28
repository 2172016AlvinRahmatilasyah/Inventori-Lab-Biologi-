@extends('layouts.app')

@section('title', 'Edit Supkonproy')

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
            <div class="card-header text-center">Edit {{ $jenis }}</div>
            @if (Session::has('fail'))
                <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
            @endif
            <div class="card-body">
                <form action="{{ route('EditSupkonpro', ['id' => $supkonpros->id, 'jenis' => $supkonpros->jenis]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="supkonpro_id" value="{{ $supkonpros->id }}">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" value="{{ $supkonpros->nama }}"
                          class="form-control" placeholder="Enter Nama">
                        @error('nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">alamat</label>
                        <input type="text" name="alamat" id="alamat" value="{{ $supkonpros->alamat }}"
                          class="form-control" placeholder="Enter alamat">
                        @error('alamat')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kota" class="form-label">kota</label>
                        <input type="text" name="kota" id="kota" class="form-control" 
                          value="{{ $supkonpros->kota }}" placeholder="Enter kota">
                        @error('kota')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="telepon" class="form-label">telepon</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" 
                          value="{{ $supkonpros->telepon }}" placeholder="Enter telepon">
                        @error('telepon')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">email</label>
                        <input type="email" name="email" id="email" class="form-control" 
                         value="{{ $supkonpros->email }}" placeholder="Enter email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis</label>
                        <select name="jenis" id="jenis" class="form-control">
                            <option value="" disabled {{ is_null($supkonpros->jenis) ? 'selected' : '' }}>Select jenis</option>
                            <option value="supplier" {{ $supkonpros->jenis === 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="konsumen" {{ $supkonpros->jenis === 'konsumen' ? 'selected' : '' }}>Konsumen</option>
                            <option value="proyek" {{ $supkonpros->jenis === 'proyek' ? 'selected' : '' }}>Proyek</option>
                        </select>
                        @error('jenis')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="" disabled {{ is_null($supkonpros->status) ? 'selected' : '' }}>Select status</option>
                            <option value="aktif" {{ $supkonpros->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $supkonpros->status === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Save</button>
                    <button type="button" class="btn btn-danger w-100" onclick="history.back()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection
