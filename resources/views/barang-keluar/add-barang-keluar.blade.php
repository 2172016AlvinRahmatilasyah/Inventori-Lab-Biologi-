@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')

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
        <div class="card-header text-center">Tambah Barang Keluar</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('AddBarangKeluar') }}" method="post">
                @csrf
                <!-- Input Tanggal -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                    @error('tanggal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Invoice -->
                <div class="mb-3">
                    <label for="invoice" class="form-label">Invoice</label>
                    <input type="text" name="invoice" id="invoice" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="jenis_id" class="form-label">Jenis Barang Keluar</label>
                    <select name="jenis_id" class="form-control select2" id="jenis_id">
                        <option value="">Pilih Jenis Barang Keluar</option>
                        @foreach ($all_jenis_pengeluarans as $jenis_pengeluaran)
                            <option value="{{ $jenis_pengeluaran->id }}" data-jenis="{{ $jenis_pengeluaran->jenis }}">
                                {{ $jenis_pengeluaran->jenis }} (ID: {{ $jenis_pengeluaran->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <!-- SupKonPro -->
                <div class="mb-3">
                    <label for="supkonpro_id" class="form-label">SupKonProy</label>
                    <select name="supkonpro_id" class="form-control select2" id="supkonpro_id">
                        <option value="">Pilih Jenis SupKonProy</option>
                        @foreach ($all_supkonpros as $supkonpro)
                            <option value="{{ $supkonpro->id }}" data-jenis="{{ $supkonpro->jenis }}">
                                {{ $supkonpro->jenis }}  (Nama: {{ $supkonpro->nama }}) 
                                (ID: {{ $supkonpro->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('supkonpro_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label">Nama</label>
                    <select class="form-control" disabled>
                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    </select>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    @error('user_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="nama_pengambil" class="form-label">Nama Pengambil</label>
                    <input type="text" name="nama_pengambil" id="nama_pengambil" 
                            value="{{ old('nama_pengambil') }}" class="form-control" 
                            placeholder="Enter Nama Pengambil">
                    @error('nama_pengambil')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Dynamic Barang Inputs -->
                <div id="barang-container">
                    <div class="barang-entry mb-3">
                        <label for="barang_id" class="form-label">Nama Barang</label>
                        <select name="barang_id[]" class="form-control select2 barang-select">
                            <option value="">Pilih Nama Barang</option>
                            @foreach ($all_barangs as $barang)
                                <option value="{{ $barang->id }}">
                                    {{ $barang->nama_barang }} (ID: {{ $barang->id }})
                                </option>
                            @endforeach
                        </select>
                        <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar[]" class="form-control jumlah-keluar" placeholder="Enter jumlah">
                
                        <label for="harga" class="form-label">Harga</label>
                        <input type="text" name="harga[]" class="form-control harga" placeholder="Enter harga">
                
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input type="text" name="total_harga[]" class="form-control total-harga" readonly placeholder="Enter total harga">
                    </div>
                </div>
        
                <button type="button" id="add-barang-btn" class="btn btn-secondary mb-3">+ Barang</button>
                
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}" class="form-control" placeholder="Enter keterangan">
                    @error('keterangan')
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
        $('.select2').select2();
        $('#tanggal').on('change', function() {
            var tanggal = $(this).val();  // Format: YYYY-MM-DD
            if (tanggal) {
                // Format tanggal menjadi YYMMDD
                var dateParts = tanggal.split('-');
                var formattedDate = dateParts[2].slice(-2) + dateParts[1] + dateParts[0].slice(-2); // Format: DDMMYY

                // Menghitung nomor urut berdasarkan tanggal yang dipilih
                $.ajax({
                    url: "{{ route('generateInvoicePengeluaran') }}",  // Pastikan ini sesuai dengan route yang benar
                    method: "GET",
                    data: { tanggal: tanggal },
                    success: function(response) {
                        var noUrut = response.noUrut;
                        $('#invoice').val(formattedDate + noUrut);  // Gabungkan tanggal + nomor urut
                    }
                });
            }
        });
        // Add new barang input field
        $('#add-barang-btn').click(function() {
            $('#barang-container').append(`
                <div class="barang-entry mb-3">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select name="barang_id[]" class="form-control select2 barang-select">
                        <option value="">Pilih Nama Barang</option>
                        @foreach ($all_barangs as $barang)
                            <option value="{{ $barang->id }}" data-id="{{ $barang->id }}">
                                {{ $barang->nama_barang }} (ID: {{ $barang->id }})
                            </option>
                        @endforeach
                    </select>
                    <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar[]" class="form-control jumlah-keluar" placeholder="Enter jumlah">
                
                        <label for="harga" class="form-label">Harga</label>
                        <input type="text" name="harga[]" class="form-control harga" placeholder="Enter harga">
                
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input type="text" name="total_harga[]" class="form-control total-harga" readonly placeholder="Enter total harga">
                    </div>
            `);
            $('.select2').select2();
        });

        $(document).on('input', '.jumlah-keluar, .harga', function() {
            var totalHarga = 0;
            $('.barang-entry').each(function() {
                var jumlahDiterima = $(this).find('.jumlah-keluar').val();
                var harga = $(this).find('.harga').val().replace(/\./g, '');
                if (jumlahDiterima && harga) {
                    totalHarga = jumlahDiterima * parseFloat(harga);
                    $(this).find('.total-harga').val(totalHarga.toLocaleString());
                }
            });
        });
    });
</script>
@endsection
