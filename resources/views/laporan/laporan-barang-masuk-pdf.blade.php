<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk</title>
    <style>
        @page {
            size: A4 landscape; /* Set the page size to A4 Landscape */
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        h1, h3 {
            text-align: center;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            word-wrap: break-word;
        }

        p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Laporan Barang Masuk</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <br>
    
    <h3>Transaksi Barang Masuk</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Id detail</th>
                <th>Id master</th>
                <th>SupKonProy</th>
                <th>Nama Staff</th>
                <th>Jenis Penerimaan</th>
                <th>Nama Pengantar</th>
                <th>Keterangan</th>
                <th>Nama Barang</th>
                <th>Jumlah Diterima</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Tanggal Ditambah</th>
                <th>Tanggal Diupdate</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($barangMasuk) && count($barangMasuk) > 0)
            @foreach ($barangMasuk as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->PenerimaanBarang->id ?? 'N/A' }}</td>
                        <td>{{ $item->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                        <td>{{ $item->PenerimaanBarang->user->name ?? 'N/A'}}</td>
                        <td>{{ $item->PenerimaanBarang->jenispenerimaanbarang->jenis  ?? 'N/A' }}</td>
                        <td>{{ $item->PenerimaanBarang->nama_pengantar ?? 'N/A'}}</td>
                        <td>{{ $item->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                        <td>{{ $item->barang->nama_barang ?? 'N/A'}}</td>
                        <td>{{ $item->jumlah_diterima }}</td>
                        <td>{{ number_format($item->harga, 0, ',', '.') ?? 'N/A'}}</td>
                        <td>{{ number_format($item->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                    </tr>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="13">Tidak Ada Transaksi Barang Masuk!</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>