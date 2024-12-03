@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h2>Daftar Barang Masuk</h2>
            <a href="{{ route('AddBarangMasuk') }}" class="btn btn-success btn-sm ml-auto">Add Barang Masuk</a>
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

        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('master-barang-masuk.search') }}" method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" placeholder="Search here" value="{{ request()->get('query') }}">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('master-barang-masuk') }}" class="btn btn-secondary ml-3">Reset</a>
                </form>            
            </div>
            <div class="col-md-6">
                <form id="perPageForm" class="d-flex mt-3">
                    <label for="perPage" class="mr-2">Items per Page:</label>
                    <select name="perPage" id="perPage" class="form-control w-auto">
                        <option value="5" {{ request('perPage') == '5' ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                        <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                </form>                
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id master</th>
                            <th>Id detail</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>SupKonProy</th>
                            <th>Nama Staff</th>
                            <th>Jenis Penerimaan</th>
                            <th>Nama Pengantar</th>
                            <th>Keterangan</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Diterima</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                            <th>Harga Invoice</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($all_detail_penerimaans) && count($all_detail_penerimaans) > 0)
                            @php
                                $offset = ($all_detail_penerimaans->currentPage() - 1) * $all_detail_penerimaans->perPage();
                            @endphp
                    
                            @foreach ($all_detail_penerimaans as $barang)
                                <tr>
                                    <td>{{ $offset + $loop->iteration }}</td> <!-- Menggunakan offset + iteration -->
                                    <td>{{ $barang->penerimaanBarang->id ?? 'N/A' }}</td>
                                    <td>{{ $barang->id }}</td>
                                    <td>{{ $barang->penerimaanBarang->invoice ?? 'N/A' }}</td>
                                    <td>{{ $barang->penerimaanBarang->tanggal ?? 'N/A' }}</td>
                                    <td>{{ $barang->penerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                                    <td>{{ $barang->penerimaanBarang->user->name ?? 'N/A' }}</td>
                                    <td>{{ $barang->penerimaanBarang->jenispenerimaanbarang->jenis ?? 'N/A' }}</td>
                                    <td>{{ $barang->penerimaanBarang->nama_pengantar }}</td>
                                    <td>{{ $barang->penerimaanBarang->keterangan }}</td>
                                    <td>{{ $barang->barang->nama_barang ?? 'N/A' }}</td>
                                    <td style="text-align: right;">{{ $barang->jumlah_diterima }}</td>
                                    <td style="text-align: right;">{{ number_format($barang->harga , 0, ',', '.')}}</td>
                                    <td style="text-align: right;">{{ number_format($barang->total_harga , 0, ',', '.')}}</td>
                                    <td style="text-align: right;">{{ number_format($barang->penerimaanBarang->harga_invoice , 0, ',', '.')}}</td>
                                    <td><a href="/edit-penerimaan-barang/{{ $barang->id }}" class="btn btn-primary btn-sm">Edit</a></td>
                                    {{-- <td><a href="/delete-penerimaan-barang/{{ $barang->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a></td> --}}
                                    <td>
                                        <a href="{{ route('deletePenerimaanBarang', $barang->id) }}" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus detail ini?')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">Data tidak ditemukan!</td>
                            </tr>
                        @endif
                    </tbody>                    
                </table>
            </div>
            <div class="pagination" style="margin-top: 20px;">
                <!-- Previous Button -->
                @if($all_detail_penerimaans->currentPage() > 1)
                    <a href="{{ $all_detail_penerimaans->previousPageUrl() }}" class="btn btn-primary" style="margin-right: 10px;">Previous</a>
                @endif
            
                <!-- Next Button -->
                @if($all_detail_penerimaans->hasMorePages())
                    <a href="{{ $all_detail_penerimaans->nextPageUrl() }}" class="btn btn-primary">Next</a>
                @endif
            </div>       
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#perPage').change(function() {
            var perPage = $(this).val();
            var currentUrl = new URL(window.location.href); // Ambil URL saat ini
            currentUrl.searchParams.set('perPage', perPage); // Update parameter 'perPage'
            window.location.href = currentUrl.toString(); // Redirect ke URL baru dengan 'perPage'
        });
    });
</script>

@endsection
