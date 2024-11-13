@extends('layouts.app')

@section('title', 'Laporan Perubahan Persediaan')

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
                <h2>Laporan Perubahan Persediaan</h2>
            </div>
            {{-- @if(Session::has('success'))
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
                <form action="{{ route('detailBarangMasuk.search') }}" method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" placeholder="Search here">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('detail-barang-masuk') }}" class="btn btn-secondary ml-3 ">Reset</a>
                </form>
            </div> --}}
                
            <div class="card-body">
                <h5>Laporan Transaksi Barang Masuk Bulan Ini</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id detail</th>
                                <th>Id master</th>
                                <th>SupKonProy</th>
                                <th>Nama Staff</th>
                                <th>Jenis Penerimaan</th>
                                <th>Nama Pengantar</th>
                                <th>Keterangan</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Diterima</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($detailPenerimaan) && count($detailPenerimaan) > 0)
                            @foreach ($detailPenerimaan as $penerimaan)
                                <tr>
                                    <td>{{ $penerimaan->id }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->id ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->user->name ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->jenispenerimaanbarang->jenis ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->nama_pengantar ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->barang->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->jumlah_diterima }}</td>
                                    <td>{{ number_format($penerimaan->harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ number_format($penerimaan->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ $penerimaan->created_at }}</td>
                                    <td>{{ $penerimaan->updated_at }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="13">Data tidak ada!</td>
                            </tr>
                        @endif                        
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-body">
                <h5>Laporan Transaksi Barang Keluar Bulan Ini</h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($detailPengeluaran) && count($detailPengeluaran) > 0)
                            @foreach ($detailPengeluaran as $pengeluaran)
                                <tr>
                                    <td>{{ $pengeluaran->id }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->id ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->user->name ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->jenispengeluaranbarang->jenis ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->nama_pengambil ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->PengeluaranBarang->keterangan ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->barang->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->jumlah_keluar }}</td>
                                    <td>{{ number_format($pengeluaran->harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ number_format($pengeluaran->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                                    <td>{{ $pengeluaran->created_at }}</td>
                                    <td>{{ $pengeluaran->updated_at }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="13">Data tidak ada!</td>
                            </tr>
                        @endif                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection