@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')

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
        <div class="card-header text-center">Tambah Barang Masuk</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('AddBarangMasuk') }}" method="post">
                @csrf
                <!-- Input Tanggal -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Penerimaan</label>
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
                    <label for="jenis_id" class="form-label">Jenis Barang Masuk</label>
                    <select name="jenis_id" class="form-control select2" id="jenis_id">
                        <option value="">Pilih Jenis Barang Masuk</option>
                        @foreach ($all_jenis_penerimaans as $jenis_penerimaan)
                            <option value="{{ $jenis_penerimaan->id }}" data-jenis="{{ $jenis_penerimaan->jenis }}">
                                {{ $jenis_penerimaan->jenis }} (ID: {{ $jenis_penerimaan->id }})
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
                    <label for="nama_pengantar" class="form-label">Nama Pengantar</label>
                    <input type="text" name="nama_pengantar" id="nama_pengantar" value="{{ old('nama_pengantar') }}" class="form-control" placeholder="Enter Nama Pengantar">
                    @error('nama_pengantar')
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
                        <label for="jumlah_diterima" class="form-label">Jumlah Diterima</label>
                        <input type="number" name="jumlah_diterima[]" class="form-control jumlah-diterima" placeholder="Enter jumlah">
                
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

                <div class="mb-3">
                    <label for="harga_invoice" class="form-label">Harga Invoice</label>
                    <input type="text" name="harga_invoice" id="harga_invoice" 
                           value="{{ old('harga_invoice') }}" class="form-control" 
                           placeholder="Enter Harga Invoice" readonly>
                    @error('harga_invoice')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>    
                
                <button type="submit" class="btn btn-primary w-100">Save</button>
                <div id="barang-container"></div>
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
                    url: "{{ route('generateInvoicePenerimaan') }}",
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
            var newBarangEntry = `
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
                    <label for="jumlah_diterima" class="form-label">Jumlah Diterima</label>
                    <input type="number" name="jumlah_diterima[]" class="form-control jumlah-diterima" placeholder="Enter jumlah">
                    
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" name="harga[]" class="form-control harga" placeholder="Enter harga">
                    
                    <label for="total_harga" class="form-label">Total Harga</label>
                    <input type="text" name="total_harga[]" class="form-control total-harga" readonly placeholder="Enter total harga">
                    
                    <!-- Tombol Batal untuk menghapus input -->
                    <button type="button" class="btn btn-danger mt-2 remove-barang-btn">Batal</button>
                </div>
            `;
            $('#barang-container').append(newBarangEntry);
            $('.select2').select2();

            // Tombol untuk membatalkan dan menghapus input barang
            $(document).on('click', '.remove-barang-btn', function() {
                $(this).closest('.barang-entry').remove();
            });
        });

        // Calculate total harga when harga or jumlah diterima changes
        // $(document).on('input', '.jumlah-diterima, .harga', function() {
        //     var totalHarga = 0;
        //     $('.barang-entry').each(function() {
        //         var jumlahDiterima = $(this).find('.jumlah-diterima').val();
        //         var harga = $(this).find('.harga').val().replace(/\./g, '');
        //         if (jumlahDiterima && harga) {
        //             totalHarga = jumlahDiterima * parseFloat(harga);
        //             $(this).find('.total-harga').val(totalHarga.toLocaleString());
        //         }
        //     });
        // });
        $(document).on('input', '.jumlah-diterima, .harga', function() {
            var totalHarga = 0;
            var totalInvoice = 0;

            // Loop untuk setiap barang entry
            $('.barang-entry').each(function() {
                var jumlahDiterima = $(this).find('.jumlah-diterima').val();
                var harga = $(this).find('.harga').val().replace(/\./g, '');
                if (jumlahDiterima && harga) {
                    var total = jumlahDiterima * parseFloat(harga);
                    $(this).find('.total-harga').val(total.toLocaleString());  // Set total harga untuk setiap barang
                    totalInvoice += total;  // Tambahkan ke total harga invoice
                }
            });

            // Update input harga_invoice dengan total seluruh barang
            $('#harga_invoice').val(totalInvoice.toLocaleString());  // Format angka dengan koma sebagai pemisah ribuan
        });
    });
</script>
@endsection
