@extends('layouts.app')

@section('title', 'Laporan Kartu Stok')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<style>
    /* Untuk merapikan tombol filter dan PDF */
    .form-row .col-auto {
        margin-bottom: 10px; /* Memberikan jarak antar elemen */
    }

    .btn {
        padding: 8px 20px; /* Memberikan padding yang seragam */
        font-size: 14px; /* Menyesuaikan ukuran font */
        border-radius: 5px; /* Membuat sudut tombol lebih halus */
    }

    /* Atur tombol PDF untuk warna dan ukuran */
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* Responsif untuk mobile */
    @media (max-width: 768px) {
        .form-row {
            flex-wrap: wrap;
        }

        .form-row .col-auto {
            flex: 0 0 100%; /* Setiap elemen menjadi satu baris di mobile */
            max-width: 100%;
        }

        .btn {
            width: 100%; /* Tombol penuh di mobile */
        }
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h2>Laporan Kartu Stok</h2>
        </div>

        <!-- Filter Form -->
        <div class="card-body">
            <!-- Filter Form -->
            <form method="get" action="{{ route('laporan-kartu-stok') }}">
                @csrf 
                <div class="form-row align-items-center">
                    <!-- Filter Tahun -->
                    <div class="col-auto">
                        <select name="tahun" class="form-control" id="tahun">
                            <option value="">Pilih Tahun</option>
                            @for ($i = 2023; $i <= now()->year+3; $i++)
                                <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="col-auto">
                        <select name="bulan" class="form-control" id="bulan">
                            <option value="">Pilih Bulan</option>
                            <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                            <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                            <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                            <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                            <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                            <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                            <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                            <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                            <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>

                    <!-- Filter Barang -->
                    <div class="col-auto">
                        <select name="barang_id" class="form-control select2" id="barang_id">
                            <option value="">Pilih atau ketik nama barang</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>

                    <!-- PDF Button -->
                    <div class="col-auto">
                        <a href="{{ route('laporan-kartu-stok-pdf') }}?tahun={{ request('tahun') }}&bulan={{ request('bulan') }}&barang_id={{ request('barang_id') }}" class="btn btn-danger">Download PDF</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table for Combined Barang Masuk and Barang Keluar -->
        <div class="card-body">
            <h5>Periode: {{ $startDate->format('d F Y') }} sampai {{ $endDate->format('d F Y') }}</h5>
            @if($allData->isNotEmpty())
                <h5>No Catalog: {{ $allData->first()->barang ? $allData->first()->barang->no_catalog : 'Data Tidak Tersedia' }}</h5>
                <h5>Nama Barang: {{ $allData->first()->barang ? $allData->first()->barang->nama_barang : 'Data Tidak Tersedia' }}</h5>  
            @else
                <h5>Data Tidak Tersedia</h5>
            @endif
            <h5>Saldo Awal: {{ $saldoAwal ?? 0 }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>SupKonProy</th>
                            <th>Jumlah Masuk</th>
                            <th>Jumlah Keluar</th>
                            <th>Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($allData) && count($allData) > 0)
                            @php
                                // Set saldo awal untuk baris pertama
                                $saldoAkhir = $saldoAwal;
                            @endphp
                            @foreach ($allData as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->PenerimaanBarang->tanggal ?? $data->PengeluaranBarang->tanggal ?? 'N/A' }}</td>
                                    <td>{{ $data->PenerimaanBarang->invoice ?? $data->PengeluaranBarang->invoice ?? 'N/A' }}</td>
                                    <td>{{ $data->PenerimaanBarang->supkonpro->nama ?? $data->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                                    <td style="text-align: right;">{{ $data->jumlah_diterima ?? 0 }}</td>
                                    <td style="text-align: right;">{{ $data->jumlah_keluar ?? 0 }}</td>
                                    <td style="text-align: right;">
                                        @php
                                            // Hitung saldo akhir berdasarkan saldo awal + jumlah diterima - jumlah keluar
                                            $saldoAkhir = $saldoAkhir + ($data->jumlah_diterima ?? 0) - ($data->jumlah_keluar ?? 0);
                                        @endphp
                                        {{ number_format($saldoAkhir, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">Data tidak ada!</td>
                            </tr>
                        @endif                        
                    </tbody>
                </table>
            </div>
        </div>        
    </div> 
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih atau ketik nama barang",
            allowClear: true
        });
    });
    
    document.addEventListener('DOMContentLoaded', function () {
            const filterSelect = document.getElementById('filter');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Function to toggle date inputs based on the selected filter
            function toggleDateInputs() {
                if (filterSelect.value === 'custom_dates') {
                    startDateInput.disabled = false;
                    endDateInput.disabled = false;
                } else {
                    startDateInput.disabled = true;
                    endDateInput.disabled = true;
                }
            }

            // Initialize the date inputs based on the current selected filter
            toggleDateInputs();

            // Add event listener for filter changes
            filterSelect.addEventListener('change', toggleDateInputs);
    });
</script>
@endsection
