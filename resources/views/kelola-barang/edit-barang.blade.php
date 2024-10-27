@extends('layouts.app')

@section('title', 'Edit Barang')

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
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header text-center">Edit Barang</div>
            @if (Session::has('fail'))
                <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
            @endif
            <div class="card-body">
                <form action="{{ route('EditBarang') }}" method="post">
                    @csrf
                    @method('PUT') 
                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" 
                               class="form-control" id="formGroupExampleInput" 
                               placeholder="Enter Nama barang">
                        @error('nama_barang')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_barang_id" class="form-label">Jenis Id Barang</label>
                        <select name="jenis_barang_id" class="form-control select2" id="jenis_barang_id">
                            <option value="">Pilih Jenis Barang</option>
                            @foreach ($jenis_barangs as $jenis_barang)
                                <option value="{{ $jenis_barang->id }}" 
                                    {{ old('jenis_barang_id', $barang->jenis_barang_id) == $jenis_barang->id ? 'selected' : '' }}>
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
                        <input type="number" name="stok" id="stok" class="form-control" 
                          value="{{ $barang->stok }}" placeholder="Enter stok barang">
                        @error('stok')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="kadaluarsa-container">
                        <label for="kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" name="kadaluarsa" id="kadaluarsa" 
                          value="{{$barang->kadaluarsa}}" class="form-control" 
                          placeholder="Enter tanggal kadaluarsa">
                        @error('kadaluarsa')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control" 
                         value="{{ $barang->lokasi }}" placeholder="Enter lokasi barang">
                        @error('lokasi')
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
                var satuanStok = $(this).find(':selected').data('satuan_stok');
    
                // Optionally handle satuanStok (assuming a hidden input or display field exists)
                // $('#stok').val(satuanStok || '');  // You can add a field for satuan_stok if needed
    
                // Check if the selected jenis_barang is "alat"
                if (namaJenisBarang && namaJenisBarang.toLowerCase() === 'alat') {
                    $('#kadaluarsa-container').hide();  // Hide the kadaluarsa input
                } else {
                    $('#kadaluarsa-container').show();  // Show the kadaluarsa input
                }
            });
        });
    </script>
@endsection
