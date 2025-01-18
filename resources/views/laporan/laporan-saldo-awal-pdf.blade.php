<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mutasi Barang</title>
    <style>
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
        h1, h3 {
            text-align: center;
        }
        tfoot, th {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        th {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        tfoot th {
            padding: 10px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Laporan Mutasi Barang</h1>
    <br>
    
    <p> Periode: {{ $startDate }} - {{ $endDate }}</p>
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <br>
    
    <p><strong>Filter:</strong></p>
    <ul>
        <li>Tahun: {{ $tahun ?? 'Semua Tahun' }}</li>
        <li>Bulan: {{ $bulan ?? 'Semua Bulan' }}</li>
    </ul>
    <h3>Data Saldo Awal</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Catalog</th>
                <th>Nama Barang</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Saldo Awal</th>
                <th>Total Terima</th>
                <th>Total Keluar</th>
                <th>Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @if ($allSaldoAwals->isNotEmpty())
                @foreach ($allSaldoAwals as $saldo_awal)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $saldo_awal->barang->no_catalog ?? 'N/A' }}</td>
                        <td>{{ $saldo_awal->barang->nama_barang ?? 'N/A' }}</td>
                        <td>{{ $saldo_awal->tahun }}</td>
                        <td>{{ $saldo_awal->bulan }}</td>
                        <td class="text-right">{{ number_format($saldo_awal->saldo_awal, 2, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($saldo_awal->total_terima, 2, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($saldo_awal->total_keluar, 2, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($saldo_awal->saldo_akhir, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9">Data tidak ada!</td>
                </tr>
            @endif
        </tbody>
    </table>              
</body>
</html>
