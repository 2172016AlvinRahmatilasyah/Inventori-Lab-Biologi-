@extends('layouts.app')

@section('title', 'Detail Transaksi Barang')

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
                <h2>Detail Transaksi Barang {{ $barang->nama_barang }}</h2>
                {{-- <a href="/add-barang-masuk" class="btn btn-success btn-sm ml-auto">Tambah Barang Masuk</a> --}}
                {{-- <a href="{{ route('AddBarangMasuk') }}" class="btn btn-success btn-sm ml-auto">Add Barang Masuk</a> --}}
            </div>
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
        
            {{-- <div class="mb-3">
                <form action="{{ route('detailBarangMasuk.search') }}" method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" placeholder="Search here">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('detail-barang-masuk') }}" class="btn btn-secondary ml-3 ">Reset</a>
                </form>
            </div> --}}
                
            <div class="card-body">
                <h5>Detail Transaksi Barang Masuk</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id master</th>
                                <th>Id detail</th>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>SupKonProy</th>
                                <th>Nama Staff</th>
                                <th>Jenis Penerimaan</th>
                                <th>Nama Pengantar</th>
                                <th>Keterangan</th>
                                <th>Jumlah Diterima</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                {{-- <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($all_master_penerimaans) && count($all_master_penerimaans) > 0)
                                @foreach ($all_master_penerimaans as $master_barang)
                                    @foreach ($master_barang->detailpenerimaanbarang as $detail)
                                        <tr>
                                            <td>{{ $master_barang->id }}</td>
                                            <td>{{ $detail->id }}</td>
                                            <td>{{ $master_barang->invoice }}</td>
                                            <td>{{ $master_barang->tanggal }}</td>
                                            <td>{{ $master_barang->supkonpro->nama ?? 'N/A' }}</td>
                                            <td>{{ $master_barang->user->name }}</td>
                                            <td>{{ $master_barang->jenispenerimaanbarang->jenis ?? 'N/A' }}</td>
                                            <td>{{ $master_barang->nama_pengantar }}</td>
                                            <td>{{ $master_barang->keterangan }}</td>
                                            <td style="text-align: right;">{{ $detail->jumlah_diterima ?? 'N/A' }}</td>
                                            <td style="text-align: right;">{{number_format($detail->harga, 0, ',', '.') ?? 'N/A' }}</td>
                                            <td style="text-align: right;">{{number_format($detail->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                                            {{-- <td>{{ $master_barang->created_at }}</td>
                                            <td>{{ $master_barang->updated_at }}</td> --}}
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">Data tidak ditemukan!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-body">
                <h5>Detail Transaksi Barang Keluar</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id master</th>
                                <th>Id detail</th>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>SupKonProy</th>
                                <th>Nama Staff</th>
                                <th>Jenis Pengeluaran</th>
                                <th>Nama Pengambil</th>
                                <th>Keterangan</th>
                                <th>Jumlah Keluar</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                {{-- <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($all_master_pengeluarans) && count($all_master_pengeluarans) > 0)
                                @foreach ($all_master_pengeluarans as $master_barang2)
                                    @foreach ($master_barang2->detailpengeluaranbarang as $detail2)
                                        <tr>
                                            <td>{{ $master_barang2->id }}</td>
                                            <td>{{ $detail2->id }}</td>
                                            <td>{{ $master_barang2->invoice }}</td>
                                            <td>{{ $master_barang2->tanggal }}</td>
                                            <td>{{ $master_barang2->supkonpro->nama ?? 'N/A' }}</td>
                                            <td>{{ $master_barang2->user->name }}</td>
                                            <td>{{ $master_barang2->jenispengeluaranbarang->jenis ?? 'N/A' }}</td>
                                            <td>{{ $master_barang2->nama_pengambil }}</td>
                                            <td>{{ $master_barang2->keterangan }}</td>
                                            <td style="text-align: right;">{{ $detail2->jumlah_keluar ?? 'N/A' }}</td>
                                            <td style="text-align: right;">{{number_format($detail2->harga, 0, ',', '.') ?? 'N/A' }}</td>
                                            <td style="text-align: right;">{{number_format($detail2->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                                            {{-- <td>{{ $master_barang2->created_at }}</td>
                                            <td>{{ $master_barang2->updated_at }}</td> --}}
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">Data tidak ada!</td>
                                </tr>
                            @endif
                        </tbody>
                        
                        
                    </table>
                </div>
            </div>


        </div>
    </div>
@endsection