@extends('layouts.app')

@section('title', 'All Detail Barang Masuk')

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

        <div class="card">
  
        
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
                <form action="{{ route('detail-barang-masuk.search') }}" method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" placeholder="Search here">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('index-detail-barang-masuk') }}" class="btn btn-secondary ml-3 ">Reset</a>
                </form>
            </div>
            
            
        
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Id Master</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Diterima</th>
                                <th>Harga</th>
                                <th>Total harga</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($all_detail_penerimaans) && count($all_detail_penerimaans) > 0)
                                @foreach ($all_detail_penerimaans as $detail_barang)
                                    <tr>
                                        <td>{{ $detail_barang->id }}</td>
                                        <td>{{ $detail_barang->PenerimaanBarang->id ?? 'N/A' }}</td>
                                        <td>{{ $detail_barang->barang->nama_barang ?? 'N/A'}}</td>
                                        <td>{{ $detail_barang->jumlah_diterima }}</td>
                                        <td>{{ number_format($detail_barang->harga, 0, ',', '.') ?? 'N/A'}}</td>
                                        <td>{{ number_format($detail_barang->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                                        <td>{{ $detail_barang->created_at }}</td>
                                        <td>{{ $detail_barang->updated_at }}</td>
                                    </tr>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">Data tidak ditemukan !</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    
@endsection