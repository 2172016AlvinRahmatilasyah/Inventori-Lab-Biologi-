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

<div class="container">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header text-center">Tambah Barang</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('AddBarang') }}" method="post">
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
                                {{ $jenis_barang->nama_jenis_barang }} (ID: {{ $jenis_barang->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_barang_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="decimal" name="stok" id="stok" class="form-control" 
                      value="{{ old('stok') }}" placeholder="Enter stok barang">
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
                    <input type="text" name="lokasi" id="lokasi" class="form-control" 
                     value="{{ old('lokasi') }}" placeholder="Enter lokasi barang">
                    @error('lokasi')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status_barang" class="form-label">Status Barang</label>
                    <input type="text" name="status_barang" id="status_barang" class="form-control" 
                     value="{{ old('status_barang') }}" placeholder="Enter Status Barang">
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
                
                <button type="submit" class="btn btn-primary w-100">Save</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize select2 on the select element
        $('.select2').select2(); 
        
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
    });
</script>
@endsection