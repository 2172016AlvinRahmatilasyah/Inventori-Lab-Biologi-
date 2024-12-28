@extends('layouts.app')

@section('title', 'Tambah Barang')

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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<style>
    .select2-container .select2-selection--single {
    height: calc(2.25rem + 2px); /* Menyesuaikan dengan tinggi input Bootstrap */
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.select2-container--bootstrap-5 .select2-dropdown {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
}

</style>
<div class="container">
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
                    <select name="barang_id" class="form-control select2" id="barang_id">
                        <option value="">Pilih atau ketik nama barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}">
                                {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <span class="text-danger">{{ $message }}</span>
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
                    <input type="text" name="saldo_awal" id="saldo_awal" readonly class="form-control" 
                           value="{{ old('saldo_awal') }}" placeholder="Saldo awal akan muncul otomatis">
                </div>
                
                <div class="mb-3">
                    <label for="total_terima" class="form-label">Total Terima</label>
                    <input type="text" name="total_terima" id="total_terima" readonly class="form-control"
                           value="{{ old('total_terima') }}" placeholder="Total terima akan muncul otomatis">
                </div>
                
                <div class="mb-3">
                    <label for="total_keluar" class="form-label">Total Keluar</label>
                    <input type="text" name="total_keluar" id="total_keluar" readonly class="form-control"
                           value="{{ old('total_keluar') }}" placeholder="Total keluar akan muncul otomatis">
                </div>                
                              
                <div class="mb-3">
                    <label for="saldo_akhir" class="form-label">Saldo Akhir</label>
                    <input type="text" name="saldo_akhir" id="saldo_akhir" readonly class="form-control" 
                           value="{{ old('saldo_akhir') }}" placeholder="Saldo akhir akan dihitung otomatis">
                </div>                
                
                <button type="submit" class="btn btn-primary w-100">Save</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Inisialisasi Select2 dengan pencarian
        $('#barang_id').select2({
            placeholder: "Pilih atau ketik nama barang", // Placeholder yang ditampilkan
            allowClear: true, // Izinkan pengguna untuk menghapus pilihan
            width: '100%', // Lebar penuh sesuai dengan container
            theme: 'bootstrap-5', // Gunakan tema bootstrap-5 agar konsisten
            dropdownParent: $('#barang_id').closest('.container'), // Dropdown muncul dalam container yang benar
        });

        // Fetch saldo awal dan transaksi ketika barang dipilih
        $('#barang_id, #bulan, #tahun').on('change', function () {
            fetchSaldoAwalDanTransaksi();
        });

        function fetchSaldoAwalDanTransaksi() {
            const barangId = $('#barang_id').val();
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();

            if (barangId && bulan && tahun) {
                $.ajax({
                    url: "{{ route('getSaldoAwalDanTransaksi') }}",
                    type: "GET",
                    data: { barang_id: barangId, bulan: bulan, tahun: tahun },
                    success: function (response) {
                        $('#saldo_awal').val(response.saldo_awal || 0);
                        $('#total_terima').val(response.total_terima || 0);
                        $('#total_keluar').val(response.total_keluar || 0);
                        calculateSaldoAkhir();
                    },
                    error: function () {
                        resetSaldoFields();
                    }
                });
            } else {
                resetSaldoFields();
            }
        }

        function resetSaldoFields() {
            $('#saldo_awal, #total_terima, #total_keluar').val(0);
            calculateSaldoAkhir();
        }

        function calculateSaldoAkhir() {
            const saldoAwal = parseFloat($('#saldo_awal').val()) || 0;
            const totalTerima = parseFloat($('#total_terima').val()) || 0;
            const totalKeluar = parseFloat($('#total_keluar').val()) || 0;

            const saldoAkhir = saldoAwal + totalTerima - totalKeluar;
            $('#saldo_akhir').val(saldoAkhir.toFixed(2));
        }
    });
</script>

@endsection