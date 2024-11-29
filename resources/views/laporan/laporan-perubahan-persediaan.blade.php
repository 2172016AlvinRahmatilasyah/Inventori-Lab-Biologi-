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

        <!-- Filter Form -->
        <div class="card-body">
            <form method="get" action="{{ route('laporan-perubahan-persediaan') }}">
                <div class="form-group">
                    <label for="filter">Pilih Filter</label>
                    <select name="filter" id="filter" class="form-control">
                        <option value="current_month" {{ request('filter') == 'current_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="current_year" {{ request('filter') == 'current_year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="last_30_days" {{ request('filter') == 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="last_60_days" {{ request('filter') == 'last_60_days' ? 'selected' : '' }}>60 Hari Terakhir</option>
                        <option value="last_90_days" {{ request('filter') == 'last_90_days' ? 'selected' : '' }}>90 Hari Terakhir</option>
                        <option value="last_12_months" {{ request('filter') == 'last_12_months' ? 'selected' : '' }}>12 Bulan Terakhir</option>
                        <option value="month_to_date" {{ request('filter') == 'month_to_date' ? 'selected' : '' }}>Bulan Ini Sampai Tanggal</option>
                        <option value="previous_month" {{ request('filter') == 'previous_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="previous_year" {{ request('filter') == 'previous_year' ? 'selected' : '' }}>Tahun Lalu</option>
                        <option value="year_to_date" {{ request('filter') == 'year_to_date' ? 'selected' : '' }}>Tahun Ini Sampai Tanggal</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
            </form>
        </div>

        <!-- Data Tables for Barang Masuk and Barang Keluar -->
        <div class="card-body">
            <h5>Laporan Transaksi Barang Masuk</h5>
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
            <h5>Laporan Transaksi Barang Keluar</h5>
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
     {{-- Download PDF --}}
     <form method="get" action="{{ route('laporan-perubahan-persediaan-pdf') }}">
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <button type="submit" name="download_pdf" class="btn btn-danger">Download PDF</button>
    </form>    
    
</div>
@endsection
