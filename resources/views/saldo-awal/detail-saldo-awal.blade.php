@extends('layouts.app')

@section('title', 'Detail Kartu Stok')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h2>Detail Kartu Stok: {{ $saldo_awal_details->first()->barang->nama_barang ?? 'N/A' }}</h2>
            <a href="{{ route('saldo-awal') }}" class="btn btn-secondary btn-sm ml-auto">Kembali</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Saldo Awal</th>
                            <th>Total Terima</th>
                            <th>Total Keluar</th>
                            <th>Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($saldo_awal_details->count() > 0)
                            @foreach ($saldo_awal_details as $detail)
                                <tr>
                                    <td>{{ $detail->tahun }}</td>
                                    <td>{{ $detail->bulan }}</td>
                                    <td>{{ number_format($detail->saldo_awal, 2, '.', ',') }}</td>
                                    <td>{{ number_format($detail->total_terima, 2, '.', ',') }}</td>
                                    <td>{{ number_format($detail->total_keluar, 2, '.', ',') }}</td>
                                    <td>{{ number_format($detail->saldo_akhir, 2, '.', ',') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">Data tidak ditemukan!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
