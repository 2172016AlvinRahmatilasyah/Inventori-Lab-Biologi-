@extends('layouts.app')

@section('title', 'Edit Barang Masuk')

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
        <div class="card-header text-center">Edit Barang Masuk</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('EditPenerimaanBarang', ['id' => $detail_penerimaan->id]) }}" method="post">
                @csrf
                @method('PUT') 
                <input type="hidden" name="masterPenerimaan_id" value="{{ $masterPenerimaan->id }}">
                <input type="hidden" name="detail_penerimaan_id" value="{{ $detail_penerimaan->id }}">

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Penerimaan</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" 
                        value="{{ $masterPenerimaan->tanggal }}" disabled>
                    @error('tanggal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="invoice" class="form-label">Invoice</label>
                    <input type="text" name="invoice" id="invoice" class="form-control" disabled
                    value="{{ $masterPenerimaan->invoice }}">
                    @error('invoice')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jenis_id" class="form-label">Jenis Barang Masuk</label>
                    <select name="jenis_id" class="form-control select2" id="jenis_id" disabled>
                        <option value="">Pilih Jenis Barang Masuk</option>
                        @foreach ($all_jenis_penerimaans as $jenis_penerimaan)
                            <option value="{{ $jenis_penerimaan->id }}" 
                                {{ $masterPenerimaan->jenis_id == $jenis_penerimaan->id ? 'selected' : '' }}>
                                {{ $jenis_penerimaan->jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="supkonpro_id" class="form-label">Supplier/Konsumen/Proyek</label>
                    <select name="supkonpro_id" class="form-control select2" id="supkonpro_id" disabled>
                        <option value="">Pilih Jenis Supplier/Konsumen/Proyek</option>
                        @foreach ($all_supkonpros as $supkonpro)
                            <option value="{{ $supkonpro->id }}" 
                                {{ $masterPenerimaan->supkonpro_id == $supkonpro->id ? 'selected' : '' }}>
                                {{ $supkonpro->jenis }}  (Nama: {{ $supkonpro->nama }}) 
                            </option>
                        @endforeach
                    </select>
                    @error('supkonpro_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="nama_pengantar" class="form-label">Nama Pengantar</label>
                    <input type="text" name="nama_pengantar" id="nama_pengantar" 
                           value="{{ $masterPenerimaan->nama_pengantar }}" disabled
                           class="form-control" placeholder="Enter Nama Pengantar">
                    @error('nama_pengantar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <input type="hidden" name="barang_id" value="{{ optional($detail_penerimaan)->barang_id }}">
                    <div class="mb-3">
                        <label for="barang_id" class="form-label">Nama Barang</label>
                        <select name="barang_id" class="form-control select2" id="barang_id" disabled>
                            <option value="">Pilih Nama Barang</option>
                            @foreach ($all_barangs as $barang)
                                <option value="{{ $barang->id }}" 
                                    {{ $barang->id == optional($detail_penerimaan)->barang_id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }} (ID: {{ $barang->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                     
                    <div class="mb-3">
                        <label for="jumlah_diterima" class="form-label">Jumlah Diterima</label>
                        <input type="number" name="jumlah_diterima" id="jumlah_diterima" class="form-control" 
                               value="{{ old('jumlah_diterima', $detail_penerimaan->jumlah_diterima) }}"
                               placeholder="Enter jumlah" step="any" readonly>
                        @error('jumlah_diterima')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" name="harga" id="harga" class="form-control" 
                           value="{{ number_format($masterPenerimaan->detailpenerimaanbarang->first()->harga ?? 0, 0, ',', '.') }}"
                           placeholder="Enter harga">
                    @error('harga')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="total_harga" class="form-label">Total Harga</label>
                    <input type="text" name="total_harga" id="total_harga" class="form-control"
                        value="{{ number_format($detail_penerimaan->total_harga ?? 0, 0, ',', '.') }}"
                        data-lama="{{ $detail_penerimaan->total_harga }}" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="harga_invoice" class="form-label">Harga Invoice *Otomatis akan update setelah di save*</label>
                    <input type="text" name="harga_invoice" id="harga_invoice" class="form-control" readonly
                        value="{{ number_format($masterPenerimaan->harga_invoice ?? 0, 0, ',', '.') }}"
                        data-lama="{{ $masterPenerimaan->harga_invoice }}">
                </div>                

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" 
                           value="{{ $masterPenerimaan->keterangan ?? '' }}" disabled
                           class="form-control" placeholder="Enter keterangan">
                    @error('keterangan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-2">Save</button>
                <button type="button" class="btn btn-danger w-100" onclick="history.back()">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        // Fungsi untuk menghitung total harga baru
        function calculateTotalHarga() {
            const jumlahDiterima = parseFloat($('#jumlah_diterima').val()) || 0; // Nilai default 0 jika kosong
            const harga = parseFloat(removeThousandsSeparator($('#harga').val())) || 0; // Hilangkan separator ribuan
            const totalHarga = jumlahDiterima * harga; // Hitung total harga
            $('#total_harga').val(formatNumber(totalHarga)); // Update input total_harga

            // Panggil fungsi untuk memperbarui harga_invoice
            calculateHargaInvoice(totalHarga);
        }

        // Fungsi untuk menghitung harga invoice baru
        function calculateHargaInvoice(totalHargaBaru) {
            const totalHargaLama = parseFloat(removeThousandsSeparator($('#total_harga').data('lama'))) || 0; // Ambil data lama
            const hargaInvoiceLama = parseFloat(removeThousandsSeparator($('#harga_invoice').data('lama'))) || 0; // Ambil harga invoice lama

            // Hitung harga invoice baru
            const hargaInvoiceBaru = hargaInvoiceLama - totalHargaLama + totalHargaBaru;
            $('#harga_invoice').val(formatNumber(hargaInvoiceBaru)); // Update input harga_invoice
        }

        // Fungsi untuk menghapus separator ribuan
        function removeThousandsSeparator(value) {
            if (!value) return "0";
            return value.replace(/\./g, ''); // Hapus semua titik
        }

        // Fungsi untuk memformat angka dengan separator ribuan
        function formatNumber(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Event listener untuk menghitung ulang total harga dan harga invoice
        $('#jumlah_diterima, #harga').on('input', function () {
            calculateTotalHarga(); // Hitung ulang ketika input diubah
        });

        // Format angka saat input kehilangan fokus
        $('#harga, #total_harga, #harga_invoice').on('blur', function () {
            $(this).val(formatNumber(removeThousandsSeparator($(this).val()))); // Format ulang nilai
        });


        // Panggil fungsi untuk memastikan input di-load dengan benar
        calculateTotalHarga();
    });
</script>
@endsection