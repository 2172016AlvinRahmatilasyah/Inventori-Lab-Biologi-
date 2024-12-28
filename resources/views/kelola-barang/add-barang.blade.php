@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-dropdown {
        max-height: 200px; /* Atur tinggi maksimal dropdown */
        overflow-y: auto; /* Tambahkan scroll jika konten melebihi tinggi maksimal */
        z-index: 9999; /* Pastikan dropdown berada di atas elemen lainnya */
    }
    .select2-container--bootstrap-5 .select2-selection--single {
        height: calc(2.25rem + 2px); /* Sesuaikan dengan tinggi input form */
        padding: 0.375rem 0.75rem; /* Tambahkan padding agar sejajar dengan elemen lainnya */
    }
</style>
<div class="container">

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header text-center">Tambah Barang</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('AddBarang') }}" method="post" id="barangForm">
                @csrf
                <div class="mb-3">
                    <label for="brand" class="form-label">Nama Brand Barang</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                      class="form-control" placeholder="Enter brand barang">
                    @error('brand')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}"
                      class="form-control" placeholder="Enter Nama barang">
                    @error('nama_barang')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="no_catalog" class="form-label">No Catalog</label>
                    <input type="text" name="no_catalog" id="no_catalog" value="{{ old('no_catalog') }}"
                      class="form-control" placeholder="Enter No Catalog">
                    @error('no_catalog')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jenis_barang_id" class="form-label">Jenis Id Barang</label>
                    <select name="jenis_barang_id" class="form-control select2" id="jenis_barang_id" 
                      value="{{old('jenis_barang_id')}}">
                        <option value="">Pilih Jenis Barang</option>
                        @foreach ($jenis_barangs as $jenis_barang)
                            <option value="{{ $jenis_barang->id }}" 
                                data-nama-jenis-barang="{{ $jenis_barang->nama_jenis_barang }}" 
                                data-satuan_stok="{{ $jenis_barang->satuan_stok }}">
                                {{ $jenis_barang->nama_jenis_barang }}
                                ({{ $jenis_barang->satuan_stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_barang_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" name="stok" id="stok" class="form-control stok" 
                        value="{{ old('stok') }}" placeholder="Enter stok barang" 
                        min="0" step="any">
                    @error('stok')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>                       
                
                <div class="mb-3" id="kadaluarsa-container">
                    <label for="kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                    <input type="date" name="kadaluarsa" id="kadaluarsa" 
                      value="{{old('kadaluarsa')}}" class="form-control" 
                      placeholder="Enter tanggal kadaluarsa">
                    @error('kadaluarsa')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <select name="lokasi" id="lokasi" class="form-control select2" placeholder="Enter lokasi barang">
                        <option value="">Pilih atau ketik lokasi baru</option>
                        @foreach ($lokasi_list as $lokasi)
                            <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                        @endforeach
                    </select>
                    @error('lokasi')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>            

                {{-- <div class="mb-3">
                    <label for="status_barang" class="form-label">Status Barang</label>
                    <input type="text" name="status_barang" id="status_barang" class="form-control" 
                     value="{{ old('status_barang') }}" placeholder="Enter Status Barang">
                    @error('status_barang')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}

                <div class="mb-3">
                    <label for="status_barang" class="form-label">Status Barang</label>
                    <select name="status_barang" id="status_barang" class="form-control select2" placeholder="Enter status barang">
                        <option value="">Pilih atau ketik status barang baru</option>
                        @foreach ($status_list as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status_barang')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>    

                <div class="mb-3">
                    <label for="plate" class="form-label">Plate</label>
                    <input type="text" name="plate" id="plate" class="form-control" 
                     value="{{ old('plate') }}" placeholder="Enter Plate">
                    @error('plate')
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
        // Initialize select2 on the select element
        $('.select2').select2(); 

        $('#lokasi',).select2({
            tags: true, // Izinkan menambahkan opsi baru
            placeholder: "Pilih atau ketik lokasi baru",
            allowClear: true, // Izinkan menghapus input
            dropdownAutoWidth: true, // Sesuaikan lebar dropdown
            width: '100%', // Pastikan lebar dropdown mengikuti input
            theme: 'bootstrap-5', // Gunakan tema Bootstrap 5 untuk gaya yang konsisten
        });

        $('#status_barang',).select2({
            tags: true, // Izinkan menambahkan opsi baru
            placeholder: "Pilih atau ketik status baru",
            allowClear: true, // Izinkan menghapus input
            dropdownAutoWidth: true, // Sesuaikan lebar dropdown
            width: '100%', // Pastikan lebar dropdown mengikuti input
            theme: 'bootstrap-5', // Gunakan tema Bootstrap 5 untuk gaya yang konsisten
        });

        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));

        // Handle tombol "Save" untuk membuka modal
        $('#openConfirmationModal').on('click', function () {
            confirmationModal.show(); // Tampilkan modal
        });

        // Handle tombol "Edit" untuk menutup modal
        $('#confirmationModal .btn-secondary').on('click', function () {
            confirmationModal.hide(); // Tutup modal
        });

        // Handle tombol "OK" untuk submit form
        $('#confirmSaveBtn').on('click', function () {
            $('#barangForm').submit(); // Submit form
        });

        // When a barang (item) is selected
        $('#jenis_barang_id').change(function() {
            // Get the selected option's data attributes
            var namaJenisBarang = $(this).find(':selected').data('nama-jenis-barang');
            // var satuanStok = $(this).find(':selected').data('satuan_stok');

            // Optionally handle satuanStok (assuming a hidden input or display field exists)
            // $('#stok').val(satuanStok || '');  // You can add a field for satuan_stok if needed

            // Check if the selected jenis_barang is "alat"
            if (namaJenisBarang && namaJenisBarang.toLowerCase() === 'kit') {
                $('#kadaluarsa-container').hide();  // Hide the kadaluarsa input
            } else {
                $('#kadaluarsa-container').show();  // Show the kadaluarsa input
            }
        });

        $(document).on('input', '.stok', function() {
            var value = $(this).val();
            if (value <= -1) {
                alert('Jumlah stok harus lebih dari -1');
                $(this).val(''); // Reset nilai input
            }
        });
    });
</script>
@endsection