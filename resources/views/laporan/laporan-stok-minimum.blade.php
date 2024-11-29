@extends('layouts.app')

@section('title', 'Barang Stok Mendekati/Sudah Minimum')

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
            <h2>Daftar Stok Mendekati/Sudah Minimum</h2>
        </div>     

        <div class="card-body">
            <!-- Items per page filter -->
            <div class="col-md-6 mb-3">
                <form id="perPageForm" class="d-flex mt-3">
                    <label for="perPage" class="mr-2">Items per Page:</label>
                    <select name="perPage" id="perPage" class="form-control w-auto">
                        <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>

            <!-- Table of items -->
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan Stok Barang</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($barangStokMinimal) > 0)
                            @foreach($barangStokMinimal as $barang)
                                <tr>
                                    <td>{{ ($barangStokMinimal->currentPage() - 1) * $barangStokMinimal->perPage() + $loop->iteration }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                                    <td>{{ $barang->stok }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">Tidak Ada Stok Mendekati/Sudah Minimum!</td> 
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination controls -->
            <div class="pagination" style="margin-top: 20px;">
                <!-- Previous page button -->
                @if($barangStokMinimal->currentPage() > 1)
                    <a href="{{ $barangStokMinimal->previousPageUrl() }}" class="btn btn-primary" style="margin-right: 10px;">Previous</a>
                @endif

                <!-- Next page button -->
                @if($barangStokMinimal->hasMorePages())
                    <a href="{{ $barangStokMinimal->nextPageUrl() }}" class="btn btn-primary">Next</a>
                @endif
            </div>

        </div>
    </div>
</div>

<script>
    // JavaScript to handle changing items per page
    $(document).ready(function() {
        $('#perPage').change(function() {
            var perPage = $(this).val();
            var currentUrl = window.location.href;
            var newUrl = new URL(currentUrl);
            newUrl.searchParams.set('perPage', perPage); // Set the perPage parameter
            window.location.href = newUrl.toString(); // Redirect to the updated URL
        });
    });
</script>

@endsection
