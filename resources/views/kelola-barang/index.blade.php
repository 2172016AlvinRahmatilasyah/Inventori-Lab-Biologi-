@extends('layouts.app')

@section('title', 'Kelola Barang')

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
    <!-- Page Heading -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h2>Daftar Barang</h2>
            <a href="/add-barang" class="btn btn-success btn-sm ml-auto">Tambah Barang Baru</a>
        </div>

        <!-- Flash message for success or failure -->
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

        <!-- Row for Search and Items per Page Filters -->
        <div class="row mb-3">
            <!-- Search Form -->
            <div class="col-md-6">
                <form action="{{ route('barangs.search') }}" method="GET" class="d-flex mt-3 ml-3">
                    <input type="text" name="query" class="form-control w-75" placeholder="Search here" value="{{ request('query') }}">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('kelola-barang') }}" class="btn btn-secondary ml-3">Reset</a>
                </form>
            </div>

            <!-- Items per Page Filter -->
            <div class="col-md-6">
                <form id="perPageForm" class="d-flex mt-3">
                    <label for="perPage" class="mr-2">Items per Page:</label>
                    <select name="perPage" id="perPage" class="form-control w-auto">
                        <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                        <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Jenis Barang Filter -->
        <div class="col-md-6">
            <form action="{{ route('kelola-barang') }}" method="GET" class="d-flex mt-3">
                <label for="jenisBarang" class="mr-2">Jenis Barang:</label>
                <select name="jenisBarang" id="jenisBarang" class="form-control w-auto">
                    <option value="">Semua</option>
                    @foreach($jenis_barangs as $jenis)
                        <option value="{{ $jenis->id }}" 
                            {{ request('jenisBarang') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_jenis_barang }} - {{ $jenis->satuan_stok }}
                        </option>
                    @endforeach
                </select>                
                <button type="submit" class="btn btn-primary ml-2">Filter</button>
            </form>
        </div>

        <!-- Data Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Brand</th>
                            <th>Nama Barang</th>
                            <th>No Catalog</th>
                            <th>Jenis Barang</th>
                            <th>Stok</th>
                            <th>Satuan Stok</th>
                            <th>Kadaluarsa</th>
                            <th>Lokasi</th>
                            <th>Status Barang</th>
                            <th>Plate</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($all_barangs) && count($all_barangs) > 0)
                            @php
                                $offset = ($all_barangs->currentPage() - 1) * $all_barangs->perPage(); 
                            @endphp
                    
                            @foreach ($all_barangs as $barang)
                                <tr>
                                    <td>{{ $offset + $loop->iteration }}</td> 
                                    <td>{{ $barang->brand }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->no_catalog }}</td>
                                    <td>{{ $barang->jenisBarang->nama_jenis_barang ?? 'N/A' }}</td>
                                    <td style="text-align: right;">{{ $barang->stok }}</td>
                                    <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                                    <td>{{ $barang->kadaluarsa }}</td>
                                    <td>{{ $barang->lokasi }}</td>
                                    <td>{{ $barang->status_barang }}</td>
                                    <td>{{ $barang->plate }}</td>
                                    <td><a href="/edit-barang/{{ $barang->id }}" class="btn btn-primary btn-sm">Edit</a></td>
                                    <td><a href="/detail-barang/{{ $barang->id }}" class="btn btn-info btn-sm">Detail</a></td>
                                    <td><a href="/delete-barang/{{ $barang->id }}" class="btn btn-danger btn-sm"  onclick="return confirm('Apakah Anda yakin ingin menghapus ini?')">Delete</a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="14">Barang tidak ditemukan!</td>
                            </tr>
                        @endif
                    </tbody>                    
                </table>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div class="pagination" style="margin-top: 20px;">
            <!-- Previous Button -->
            @if($all_barangs->currentPage() > 1)
                <a href="{{ $all_barangs->previousPageUrl() }}" class="btn btn-primary" style="margin-right: 10px;">Previous</a>
            @endif

            <!-- Next Button -->
            @if($all_barangs->hasMorePages())
                <a href="{{ $all_barangs->nextPageUrl() }}" class="btn btn-primary">Next</a>
            @endif
        </div>

    </div>
</div>

<script>
  $(document).ready(function() {
    $('#perPage').change(function() {
        var perPage = $(this).val();
        var currentPage = '{{ $all_barangs->currentPage() }}'; // Get current page

        // Create a new URL object
        var currentUrl = window.location.href;
        var newUrl = new URL(currentUrl);

        // Set perPage parameter and preserve the current page number
        newUrl.searchParams.set('perPage', perPage);
        newUrl.searchParams.set('page', currentPage); // Keep the current page

        // Preserve jenisBarang parameter
        var jenisBarang = $('#jenisBarang').val();
        if (jenisBarang) {
            newUrl.searchParams.set('jenisBarang', jenisBarang);
        }

        // Redirect to the new URL with updated perPage and jenisBarang
        window.location.href = newUrl.toString();
    });
});
</script>


@endsection
