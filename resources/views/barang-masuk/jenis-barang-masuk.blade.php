@extends('layouts.app')

@section('title', 'Jenis Barang Masuk')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h2>Daftar Jenis Barang Masuk</h2>
                <a href="{{ route('AddJenisBarangMasuk') }}" class="btn btn-success btn-sm ml-auto">
                    Add Jenis Barang Masuk
                </a>
            </div>
            
        
            {{-- Flash message for success or failure --}}
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
        
            <div class="mb-3">
                {{-- <form action="{{ route('jenis-barang-masuk.search') }}" method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" placeholder="Search here">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('jenis-barang-masuk') }}" class="btn btn-secondary ml-3 ">Reset</a>
                </form> --}}
            </div>
            
            
        
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Penerimaan</th>
                                {{-- <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th> --}}
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($all_jenis_penerimaans) && count($all_jenis_penerimaans) > 0)
                                @foreach ($all_jenis_penerimaans as $jenis_penerimaan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $jenis_penerimaan->jenis }}</td>
                                        <td><a href="/edit-jenis-barang-masuk/{{ $jenis_penerimaan->id }}" class="btn btn-primary btn-sm">Edit</a></td>
                                        <td>
                                            @if(!in_array($jenis_penerimaan->id, $used_jenis_ids))
                                                <a href="/delete-jenis-barang-masuk/{{ $jenis_penerimaan->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">Data tidak ditemukan!</td>
                                </tr>
                            @endif
                        </tbody>                        
                    </table>
                </div>
            </div>
        </div>

    </div>
   
@endsection