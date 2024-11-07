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
            <form action="{{ route('EditPenerimaanBarang', ['id' => $masterPenerimaan->id]) }}" method="post">
                @csrf
                @method('PUT') 
                <input type="hidden" name="masterPenerimaan_id" value="{{ $masterPenerimaan->id }}">
                
                <div class="mb-3">
                    <label for="jenis_id" class="form-label">Jenis Barang Masuk</label>
                    <select name="jenis_id" class="form-control select2" id="jenis_id">
                        <option value="">Pilih Jenis Barang Masuk</option>
                        @foreach ($all_jenis_penerimaans as $jenis_penerimaan)
                            <option value="{{ $jenis_penerimaan->id }}" 
                                {{ $masterPenerimaan->jenis_id == $jenis_penerimaan->id ? 'selected' : '' }}>
                                {{ $jenis_penerimaan->jenis }} (ID: {{ $jenis_penerimaan->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_penerimaan_barang_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="supkonpro_id" class="form-label">SupKonProy</label>
                    <select name="supkonpro_id" class="form-control select2" id="supkonpro_id">
                        <option value="">Pilih Jenis SupKonProy</option>
                        @foreach ($all_supkonpros as $supkonpro)
                            <option value="{{ $supkonpro->id }}" 
                                {{ $masterPenerimaan->supkonpro_id == $supkonpro->id ? 'selected' : '' }}>
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
                    <label for="nama_pengantar" class="form-label">Nama Pengantar</label>
                    <input type="text" name="nama_pengantar" id="nama_pengantar" 
                           value="{{ $masterPenerimaan->nama_pengantar }}"
                           class="form-control" placeholder="Enter Nama Pengantar">
                    @error('nama_pengantar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <input type="hidden" name="barang_id" value="{{ optional($masterPenerimaan->detailpenerimaanbarang->first())->barang_id }}">
                <div class="mb-3">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select name="barang_id" class="form-control select2" id="barang_id" disabled>
                        <option value="">Pilih Nama Barang</option>
                        @foreach ($all_barangs as $barang)
                            <option value="{{ $barang->id }}" 
                                {{ optional($masterPenerimaan->detailpenerimaanbarang->first())->barang_id == $barang->id ? 'selected' : '' }}>
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
                           value="{{ $masterPenerimaan->detailpenerimaanbarang->first()->jumlah_diterima ?? '' }}"
                           placeholder="Enter jumlah" step="any">
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
                         value="{{ number_format(($masterPenerimaan->detailpenerimaanbarang->first()->jumlah_diterima ?? 0) * ($masterPenerimaan->detailpenerimaanbarang->first()->harga ?? 0), 0, ',', '.') }}">
                    @error('harga')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" 
                           value="{{ $masterPenerimaan->keterangan ?? '' }}"
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
            let jumlahMasuk = parseFloat($('#jumlah_diterima').val()) || 0;
            let harga = parseFloat(removeThousandsSeparator($('#harga').val())) || 0;
            let totalHarga = jumlahMasuk * harga;
            $('#total_harga').val(formatNumber(totalHarga));
        }

        function removeThousandsSeparator(value) {
            return value.replace(/\./g, '');
        }

        function formatNumber(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $('#jumlah_diterima, #harga').on('input', function() {
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