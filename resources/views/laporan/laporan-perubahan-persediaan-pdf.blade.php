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
    <h1>Laporan Perubahan Persediaan</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <p>Berdasarkan: {{ $filter }}</p>
    <br>
    
    <h3>Data Barang Masuk</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id Master</th>
                    <th>Id Detail</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>SupKonProy</th>
                    <th>Nama Staff</th>
                    <th>Jenis Penerimaan</th>
                    <th>Nama Pengantar</th>
                    <th>Keterangan</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Diterima</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($detailPenerimaan) && count($detailPenerimaan) > 0)
                    @foreach ($detailPenerimaan as $penerimaan)
                        <tr>
                            <td>{{ $penerimaan->PenerimaanBarang->id ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->id }}</td>
                            <td>{{ $penerimaan->PenerimaanBarang->invoice }}</td>
                            <td>{{ $penerimaan->PenerimaanBarang->tanggal }}</td>
                            <td>{{ $penerimaan->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->PenerimaanBarang->user->name ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->PenerimaanBarang->jenispenerimaanbarang->jenis ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->PenerimaanBarang->nama_pengantar ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->barang->nama_barang ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->jumlah_diterima }}</td>
                            <td>{{ number_format($penerimaan->harga, 0, ',', '.') ?? 'N/A' }}</td>
                            <td>{{ number_format($penerimaan->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13">Data tidak ada!</td>
                    </tr>
                @endif                        
            </tbody>
    </table>

    <h3>Data Barang Keluar</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id Master</th>
                    <th>Id Detail</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>SupKonProy</th>
                    <th>Nama Staff</th>
                    <th>Jenis Pengeluaran</th>
                    <th>Nama Pengambil</th>
                    <th>Keterangan</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Keluar</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($detailPengeluaran) && count($detailPengeluaran) > 0)
                    @foreach ($detailPengeluaran as $pengeluaran)
                        <tr>
                            <td>{{ $pengeluaran->PengeluaranBarang->id ?? 'N/A' }}</td>
                            <td>{{ $pengeluaran->id }}</td>
                            <td>{{ $pengeluaran->PengeluaranBarang->invoice }}</td>
                            <td>{{ $pengeluaran->PengeluaranBarang->tanggal }}</td>
                            <td>{{ $pengeluaran->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                            <td>{{ $pengeluaran->PengeluaranBarang->user->name ?? 'N/A' }}</td>
                            <td>{{ $pengeluaran->PengeluaranBarang->jenispengeluaranbarang->jenis ?? 'N/A' }}</td>
                            <td>{{ $pengeluaran->PengeluaranBarang->nama_pengambil ?? 'N/A' }}</td>
                            <td>{{ $pengeluaran->PengeluaranBarang->keterangan ?? 'N/A' }}</td>
                            <td>{{ $pengeluaran->barang->nama_barang ?? 'N/A' }}</td>
                            <td>{{ $pengeluaran->jumlah_keluar }}</td>
                            <td>{{ number_format($pengeluaran->harga, 0, ',', '.') ?? 'N/A' }}</td>
                            <td>{{ number_format($pengeluaran->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13">Data tidak ada!</td>
                    </tr>
                @endif                        
            </tbody>
        </table>

</body>
</html>