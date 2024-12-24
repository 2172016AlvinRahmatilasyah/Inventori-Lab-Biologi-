<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perubahan Persediaan</title>
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
    </style>
</head>
<body>
    <h1>Laporan Perubahan Persediaan</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <p>Berdasarkan: {{ $filter }}</p>
    <p>Tanggal: {{ $startDate }} - {{ $endDate }}</p>
    <br>
    
    <h3>Data Perubahan Persediaan Barang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Tanggal</th>
                <th>SupKonPro</th>
                <th>Nama Staff</th>
                <th>Jenis Transaksi</th>
                <th>Jumlah Masuk</th>
                <th>Jumlah Keluar</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Harga Invoice</th>
            </tr>
        </thead>
        <tbody>
            @if($allData && $allData->count() > 0)
                @foreach ($allData as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->barang->nama_barang ?? 'N/A' }}</td>
                        <td>{{ $data->PenerimaanBarang->tanggal ?? $data->PengeluaranBarang->tanggal ?? 'N/A' }}</td>
                        <td>{{ $data->PenerimaanBarang->supkonpro->nama ?? $data->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                        <td>{{ $data->PenerimaanBarang->user->name ?? $data->PengeluaranBarang->user->name ?? 'N/A' }}</td>
                        <td>{{ $data->PenerimaanBarang->jenispenerimaanbarang->jenis ?? $data->PengeluaranBarang->jenispengeluaranbarang->jenis ?? 'N/A' }}</td>
                        <td class="text-right">
                            {{ $data->jumlah_diterima == 0 ? '-' : number_format($data->jumlah_diterima ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            {{ $data->jumlah_keluar == 0 ? '-' : number_format($data->jumlah_keluar ?? 0, 0, ',', '.') }}
                        </td>                        
                        <td class="text-right">{{ number_format($data->harga ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($data->total_harga ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format( $data->PenerimaanBarang->harga_invoice ?? $data->PengeluaranBarang->harga_invoice ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11">Data tidak ditemukan!</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
