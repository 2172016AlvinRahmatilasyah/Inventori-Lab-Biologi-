@extends('layouts.app')

@section('title', 'Tambah Jenis Barang Masuk')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header text-center">Tambah Jenis Barang Masuk</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('AddJenisBarangMasuk') }}" method="post" id='jenisBarangMasukForm'>
                @csrf
                
                <div class="mb-3">
                    <label for="jenis" class="form-label">Jenis barang masuk</label>
                    <input type="text" name="jenis" id="jenis" value="{{ old('jenis') }}" 
                           class="form-control" placeholder="Enter jenis">
                    @error('jenis')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="button" id="openConfirmationModal" class="btn btn-primary w-100">Save</button>
            </form>
        </div>
    </div>
</div>
<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Data</h5>
            </div>
            <div class="modal-body">
                Apakah data yang Anda masukkan sudah yakin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
                <button type="button" id="confirmSaveBtn" class="btn btn-primary">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Modal konfirmasi instance
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));

        // Handle tombol "Save" untuk membuka modal
        $('#openConfirmationModal').on('click', function () {
            confirmationModal.show();
        });

        // Handle tombol "Edit" untuk menutup modal
        $('#confirmationModal .btn-secondary').on('click', function () {
            confirmationModal.hide(); // Gunakan Bootstrap 5 Modal API untuk menutup
        });

        // Handle tombol "OK" untuk submit form
        $('#confirmSaveBtn').on('click', function () {
            $('#jenisBarangMasukForm').submit(); // Submit form
        });
    });
</script>
@endsection