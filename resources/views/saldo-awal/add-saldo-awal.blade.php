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
        <div class="card-header text-center">Tambah Saldo Awal</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('AddSaldoAwal') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select name="barang_id" class="form-control select2" id="barang_id" value="{{ old('barang_id') }}">
                        <option value="">Pilih nama barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" data-nama-barang="{{ $barang->nama_barang }}">
                                {{ $barang->nama_barang }} (ID: {{ $barang->id }})
                            </option>
                        @endforeach
                    </select>
                    
                    @error('barang_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <input type="text" name="tahun" id="tahun" class="form-control" 
                           value="{{ old('tahun') }}" placeholder="Enter tahun (contoh: 2024)">
                    @error('tahun')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Pilih bulan</option>
                        <option value="01" {{ old('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ old('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ old('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ old('bulan') == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ old('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ old('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ old('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ old('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ old('bulan') == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ old('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ old('bulan') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ old('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                    @error('bulan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                
                <div class="mb-3">
                    <label for="saldo_awal" class="form-label">Saldo Awal</label>
                    <input type="text" name="saldo_awal" id="saldo_awal" class="form-control" 
                           value="{{ old('saldo_awal') }}" placeholder="Enter saldo awal" 
                           onblur="this.value = formatNumber(this.value)">
                    @error('saldo_awal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="total_terima" class="form-label">Total Terima</label>
                    <input type="text" name="total_terima" id="total_terima" class="form-control" 
                           value="{{ old('total_terima') }}" placeholder="Enter total terima" 
                           onblur="this.value = formatNumber(this.value)">
                    @error('total_terima')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="total_keluar" class="form-label">Total Keluar</label>
                    <input type="text" name="total_keluar" id="total_keluar" class="form-control" 
                           value="{{ old('total_keluar') }}" placeholder="Enter total keluar" 
                           onblur="this.value = formatNumber(this.value)">
                    @error('total_keluar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="saldo_akhir" class="form-label">Saldo Akhir</label>
                    <input type="text" name="saldo_akhir" id="saldo_akhir" class="form-control" 
                           value="{{ old('saldo_akhir') }}" placeholder="Enter saldo akhir" 
                           onblur="this.value = formatNumber(this.value)">
                    @error('saldo_akhir')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <script>
                    function formatNumber(num) {
                        if (!num) return '';
                        num = num.replace(/\./g, '').replace(/,/g, '.'); // Remove existing dots and commas
                        return new Intl.NumberFormat('id-ID').format(num); // Format the number
                    }
                </script>
                
                <button type="submit" class="btn btn-primary w-100">Save</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endsection