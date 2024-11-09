<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
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
    <h1>{{ $title }}</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <br>
    
    <h2>Summary</h2>
    <p>Transaksi Barang Masuk Bulan Ini: {{ $barangMasukBulanIni }} Transaksi</p>
    <p>Transaksi Barang Keluar Bulan Ini: {{ $barangKeluarBulanIni }} Transaksi</p>
    <p>Total Transaksi Bulan Ini: {{ $barangMasukBulanIni + $barangKeluarBulanIni }} Transaksi</p>
    <p>Stok Mendekati/Sudah Minimum: {{ count($barangStokMinimal) }} Items</p>
    <p>Stok Mendekati Kadaluarsa: {{ count($barangKadaluarsaMendekati) }} Items</p>
    <p>Total Stok Keseluruhan: {{ $totalStok }} Items</p>
    <br>

    <h3>Transaksi Barang Masuk Bulan Ini</h3>
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
            @if(isset($detail_penerimaan) && count($detail_penerimaan) > 0)
                @foreach ($detail_penerimaan as $detail_penerimaan)
                    <tr>
                        <td>{{ $detail_penerimaan->id }}</td>
                        <td>{{ $detail_penerimaan->PenerimaanBarang->id ?? 'N/A' }}</td>
                        <td>{{ $detail_penerimaan->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                        <td>{{ $detail_penerimaan->PenerimaanBarang->user->name ?? 'N/A'}}</td>
                        <td>{{ $detail_penerimaan->PenerimaanBarang->jenispenerimaanbarang->jenis  ?? 'N/A' }}</td>
                        <td>{{ $detail_penerimaan->PenerimaanBarang->nama_pengantar ?? 'N/A'}}</td>
                        <td>{{ $detail_penerimaan->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                        <td>{{ $detail_penerimaan->barang->nama_barang ?? 'N/A'}}</td>
                        <td>{{ $detail_penerimaan->jumlah_diterima }}</td>
                        <td>{{ number_format($detail_penerimaan->harga, 0, ',', '.') ?? 'N/A'}}</td>
                        <td>{{ number_format($detail_penerimaan->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                        <td>{{ $detail_penerimaan->created_at }}</td>
                        <td>{{ $detail_penerimaan->updated_at }}</td>
                    </tr>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="13">Data tidak ada !</td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>

    <h3>Transaksi Barang Keluar Bulan Ini</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Id detail</th>
                <th>Id master</th>
                <th>SupKonProy</th>
                <th>Nama Staff</th>
                <th>Jenis Pengeluaran</th>
                <th>Nama Pengambil</th>
                <th>Keterangan</th>
                <th>Nama Barang</th>
                <th>Jumlah Keluar</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Tanggal Ditambah</th>
                <th>Tanggal Diupdate</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($detail_pengeluaran) && $detail_pengeluaran->count() > 0)
                @foreach ($detail_pengeluaran as $detail)
                    <tr>
                        <td>{{ $detail->id }}</td>
                        <td>{{ $detail->PengeluaranBarang->id ?? 'N/A' }}</td>
                        <td>{{ $detail->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                        <td>{{ $detail->PengeluaranBarang->user->name ?? 'N/A' }}</td>
                        <td>{{ $detail->PengeluaranBarang->jenisPengeluaranBarang->jenis ?? 'N/A' }}</td>
                        <td>{{ $detail->PengeluaranBarang->nama_pengambil ?? 'N/A' }}</td>
                        <td>{{ $detail->PengeluaranBarang->keterangan ?? 'N/A' }}</td>
                        <td>{{ $detail->barang->nama_barang ?? 'N/A' }}</td>
                        <td>{{ $detail->jumlah_keluar }}</td>
                        <td>{{ number_format($detail->harga, 0, ',', '.') ?? 'N/A' }}</td>
                        <td>{{ number_format($detail->total_harga, 0, ',', '.') ?? 'N/A' }}</td>
                        <td>{{ $detail->created_at }}</td>
                        <td>{{ $detail->updated_at }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="13">Data tidak ada!</td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>

    <h3>Perubahan Persediaan</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Satuan Stok Barang</th>
                <th>Jumlah Keluar</th>
                <th>Jumlah Masuk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangList as $barang)
                <tr>
                    <td>{{ $barang['nama_barang'] }}</td>
                    <td>{{ $barang['satuan_stok'] }}</td>
                    <td>{{ $barang['jumlah_keluar'] }}</td>
                    <td>{{ $barang['jumlah_masuk'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    <h3>Stok Mendekati/Sudah Minimum</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <tr>
            <th>Nama Barang</th>
            <th>Stok</th>
        </tr>
        @foreach($barangStokMinimal as $barang)
            <tr>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->stok }}</td>
            </tr>
        @endforeach
    </table>
    <br>

    <h3>Barang Mendekati Kadaluarsa</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <tr>
            <th>Nama Barang</th>
            <th>Kadaluarsa</th>
        </tr>
        @foreach($barangKadaluarsaMendekati as $barang)
            <tr>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kadaluarsa }}</td>
            </tr>
        @endforeach
    </table>
    <br>

    <h3>Statistik Total Barang</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Stok</th>
                <th>Satuan Stok</th>
                <th>Kadaluarsa</th>
                <th>Lokasi</th>
                <th>Tanggal Ditambah</th>
                <th>Tanggal Diupdate</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($all_barangs) && count($all_barangs) > 0)
                @foreach ($all_barangs as $barang)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->jenisBarang->nama_jenis_barang ?? 'N/A' }}</td>
                        <td>{{ $barang->stok }}</td>
                        <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                        <td>{{ $barang->kadaluarsa }}</td>
                        <td>{{ $barang->lokasi }}</td>
                        <td>{{ $barang->created_at }}</td>
                        <td>{{ $barang->updated_at }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="13">Barang tidak ditemukan!</td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>


</body>
</html>