<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar</title>
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
    <h1>Laporan Barang Keluar</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <br>
    
    <h3>Data Barang Keluar</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id detail</th>
                    <th>Id master</th>
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
                    {{-- <th>Tanggal Ditambah</th>
                    <th>Tanggal Diupdate</th> --}}
                </tr>
            </thead>
            <tbody>
                @if(isset($barangKeluar) && count($barangKeluar) > 0)
                @foreach ($barangKeluar as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->PengeluaranBarang->id ?? 'N/A' }}</td>
                            <td>{{ $item->PengeluaranBarang->invoice ?? 'N/A'}}</td>
                            <td>{{ $item->PengeluaranBarang->tanggal ?? 'N/A'}}</td>
                            <td>{{ $item->PengeluaranBarang->supkonpro->nama ?? 'N/A' }}</td>
                            <td>{{ $item->PengeluaranBarang->user->name ?? 'N/A'}}</td>
                            <td>{{ $item->PengeluaranBarang->jenispengeluaranbarang->jenis  ?? 'N/A' }}</td>
                            <td>{{ $item->PengeluaranBarang->nama_pengambil ?? 'N/A'}}</td>
                            <td>{{ $item->PengeluaranBarang->keterangan ?? 'N/A' }}</td>
                            <td>{{ $item->barang->nama_barang ?? 'N/A'}}</td>
                            <td>{{ $item->jumlah_keluar }}</td>
                            <td>{{ number_format($item->harga, 0, ',', '.') ?? 'N/A'}}</td>
                            <td>{{ number_format($item->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                            {{-- <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td> --}}
                        </tr>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13">Tidak Ada Transaksi Barang!</td>
                    </tr>
                @endif
            </tbody>
    </table>
</body>
</html>