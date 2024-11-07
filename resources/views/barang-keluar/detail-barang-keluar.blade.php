@extends('layouts.app')

@section('title', 'Detail Barang Keluar')

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

        <!-- Page Heading -->
        {{-- <h1 class="h3 mb-2 text-gray-800">Data Jenis Barang</h1> --}}

        <!-- DataTales Example -->
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h2>Detail Barang Keluar</h2>
                {{-- <a href="/add-barang-masuk" class="btn btn-success btn-sm ml-auto">Tambah Barang Masuk</a> --}}
                {{-- <a href="{{ route('AddBarangMasuk') }}" class="btn btn-success btn-sm ml-auto">Add Barang Masuk</a> --}}
            </div>
            
        
            {{-- Flash message for success or failure
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
        
            @if(Session::has('fail'))
                <div class="alert alert-danger">
                    {{ Session::get('fail') }}
                </div>
            @endif --}}
        
            {{-- <div class="mb-3">
                <form action="{{ route('master-barang-masuk.search') }}" method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" placeholder="Search here">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('master-barang-masuk') }}" class="btn btn-secondary ml-3 ">Reset</a>
                </form>
            </div> --}}
            
            
        
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id detail</th>
                                <th>Id master</th>
                                <th>SupKonProy</th>
                                <th>Nama Staff</th>
                                <th>Jenis Pengeluaran</th>
                                <th>Nama Pengambil</th>
                                <th>Keterangan</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Keluar</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                                {{-- <th colspan="3">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($detail_pengeluaran) && count($detail_pengeluaran) > 0)
                                @foreach ($detail_pengeluaran as $detail_pengeluaran)
                                    <tr>
                                        <td>{{ $detail_pengeluaran->id }}</td>
                                        <td>{{ $detail_pengeluaran->PengeluaranBarang->id ?? 'N/A' }}</td>
                                        <td>{{ $detail_pengeluaran->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                                        <td>{{ $detail_pengeluaran->PengeluaranBarang->user->name ?? 'N/A'}}</td>
                                        <td>{{ $detail_pengeluaran->PengeluaranBarang->jenisPengeluaranBarang->jenis  ?? 'N/A' }}</td>
                                        <td>{{ $detail_pengeluaran->PengeluaranBarang->nama_pengambil ?? 'N/A'}}</td>
                                        <td>{{ $detail_pengeluaran->PengeluaranBarang->keterangan ?? 'N/A' }}</td>
                                        <td>{{ $detail_pengeluaran->barang->nama_barang ?? 'N/A'}}</td>
                                        <td>{{ $detail_pengeluaran->jumlah_keluar }}</td>
                                        <td>{{ number_format($detail_pengeluaran->harga, 0, ',', '.') ?? 'N/A'}}</td>
                                        <td>{{ number_format($detail_pengeluaran->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                                        <td>{{ $detail_pengeluaran->created_at }}</td>
                                        <td>{{ $detail_pengeluaran->updated_at }}</td>
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
    <script>
        function formatNumber(num) {
            if (!num) return '';
            num = num.replace(/\./g, '').replace(/,/g, '.'); // Remove existing dots and commas
            return new Intl.NumberFormat('id-ID').format(num); // Format the number
        }
    </script>
@endsection