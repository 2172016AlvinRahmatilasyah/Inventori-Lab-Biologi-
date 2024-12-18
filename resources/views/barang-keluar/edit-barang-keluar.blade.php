@extends('layouts.app')

@section('title', 'Edit Barang Keluar')

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
        <div class="card-header text-center">Edit Barang Keluar</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('EditPengeluaranBarang', ['id' => $detail_pengeluaran->id]) }}" method="post">
                @csrf
                @method('PUT') 
                <input type="hidden" name="masterPengeluaran_id" value="{{ $masterPengeluaran->id }}">
                <input type="hidden" name="detail_pengeluaran_id" value="{{ $detail_pengeluaran->id }}">
                
                {{-- <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" 
                        value="{{ $masterPengeluaran->tanggal }}">
                    @error('tanggal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Input Invoice -->
                <div class="mb-3">
                    <label for="invoice" class="form-label">Invoice</label>
                    <input type="text" name="invoice" id="invoice" class="form-control" readonly
                    value="{{ $masterPengeluaran->invoice }}">
                    @error('invoice')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}

                <div class="mb-3">
                    <label for="jenis_id" class="form-label">Jenis Barang Keluar</label>
                    <select name="jenis_id" class="form-control select2" id="jenis_id">
                        <option value="">Pilih Jenis Barang Keluar</option>
                        @foreach ($all_jenis_pengeluarans as $jenis_pengeluaran)
                            <option value="{{ $jenis_pengeluaran->id }}" 
                                {{ $masterPengeluaran->jenis_id == $jenis_pengeluaran->id ? 'selected' : '' }}>
                                {{ $jenis_pengeluaran->jenis }} (ID: {{ $jenis_pengeluaran->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_pengeluaran_barang_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="supkonpro_id" class="form-label">SupKonProy</label>
                    <select name="supkonpro_id" class="form-control select2" id="supkonpro_id">
                        <option value="">Pilih Jenis SupKonProy</option>
                        @foreach ($all_supkonpros as $supkonpro)
                            <option value="{{ $supkonpro->id }}" 
                                {{ $masterPengeluaran->supkonpro_id == $supkonpro->id ? 'selected' : '' }}>
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
                    <label for="nama_pengambil" class="form-label">Nama Pengambil</label>
                    <input type="text" name="nama_pengambil" id="nama_pengambil" value="{{ $masterPengeluaran->nama_pengambil }}"
                           class="form-control" placeholder="Enter Nama Pengambil">
                    @error('nama_pengambil')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <input type="hidden" name="barang_id" value="{{ optional($detail_pengeluaran)->barang_id }}">
                <div class="mb-3">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select name="barang_id" class="form-control select2" id="barang_id" disabled>
                        <option value="">Pilih Nama Barang</option>
                        @foreach ($all_barangs as $barang)
                            <option value="{{ $barang->id }}" 
                                {{ $barang->id == optional($detail_pengeluaran)->barang_id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} (ID: {{ $barang->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                    <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control" 
                           value="{{ $detail_pengeluaran->jumlah_keluar }}"
                           placeholder="Enter jumlah" step="any">
                    @error('jumlah_keluar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" name="harga" id="harga" class="form-control" 
                           value="{{ number_format($masterPengeluaran->detailpengeluaranbarang->first()->harga ?? 0, 0, ',', '.') }}"
                           placeholder="Enter harga">
                    @error('harga')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="total_harga" class="form-label">Total Harga</label>
                    <input type="text" name="total_harga" id="total_harga" class="form-control" 
                         value="{{ number_format(($masterPengeluaran->detailpengeluaranbarang->first()->jumlah_keluar ?? 0) * ($masterPengeluaran->detailpengeluaranbarang->first()->harga ?? 0), 0, ',', '.') }}">
                    @error('harga')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" 
                           value="{{ $masterPengeluaran->keterangan ?? '' }}"
                           class="form-control" placeholder="Enter keterangan">
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

        function calculateTotal() {
            let jumlahKeluar = parseFloat($('#jumlah_keluar').val()) || 0;
            let harga = parseFloat(removeThousandsSeparator($('#harga').val())) || 0;
            let totalHarga = jumlahKeluar * harga;
            $('#total_harga').val(formatNumber(totalHarga));
        }

        function removeThousandsSeparator(value) {
            return value.replace(/\./g, '');
        }

        function formatNumber(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $('#jumlah_keluar, #harga').on('input', function() {
            calculateTotal();
        });

        $('#harga').on('blur', function() {
            $(this).val(formatNumber(removeThousandsSeparator($(this).val())));
        });

        $('#total_harga').on('blur', function() {
            $(this).val(formatNumber(removeThousandsSeparator($(this).val())));
        });
    });
</script>
@endsection