<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kartu Stok</title>
    <style>
        @page {
            size: A4 landscape; /* Set the page size to A4 Landscape */
            margin: 10mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        h1, h3{
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        tfoot, th {
            font-weight: bold;
            background-color: #f2f2f2; /* Warna latar belakang untuk pembeda */
        }
        tfoot th {
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Laporan Kartu Stok</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    @if($allData->isNotEmpty())
        <h5>No Catalog: {{ $allData->first()->barang ? $allData->first()->barang->no_catalog : 'Data Tidak Tersedia' }}</h5>
        <h5>Nama Barang: {{ $allData->first()->barang ? $allData->first()->barang->nama_barang : 'Data Tidak Tersedia' }}</h5>  
    @else
        <h5>Data Tidak Tersedia</h5>
    @endif
    <h5>Saldo Awal: {{ $saldoAwal ?? 0 }}</h5>
    <br>
    
    <h3>Data Kartu Stok</h3>
    <table>
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
</body>
</html>
