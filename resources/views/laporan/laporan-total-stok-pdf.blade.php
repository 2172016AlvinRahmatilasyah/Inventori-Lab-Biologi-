<!DOCTYPE html>
<html>
<head>
    <title>Laporan Total Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
    
        h1 {
            text-align: center;
            font-size: 16px;
        }
    
        p {
            font-size: 12px;
        }
    
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
    
        table, th, td {
            border: 1px solid black;
        }
    
        th {
            background-color: #f2f2f2;
            text-align: center;
            padding: 8px;
            font-size: 12px;
        }
    
        td {
            padding: 8px;
            font-size: 11px;
            text-align: left;
        }
    
        td:nth-child(1), td:nth-child(5) {
            text-align: center;
        }
    
        /* Untuk menghindari teks keluar dari batas kolom */
        td {
            word-wrap: break-word;
            word-break: break-word;
        }
    
        .total-row {
            font-weight: bold;
            text-align: center;
        }
    </style>    
</head>
<body>
    <h1>Laporan Total Stok Keseluruhan</h1>
    <br>
    <p>Tanggal: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Satuan Stok</th>
                <th>Stok</th>
                {{-- <th>Kadaluarsa</th>
                <th>Lokasi</th> --}}
            </tr>
        </thead>
        <tbody>
            {{-- @foreach($allBarangs as $barang)
                <tr>
                    <td>{{ $loop->iteration + (($allBarangs->currentPage() - 1) * $allBarangs->perPage()) }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->jenisBarang->nama_jenis_barang ?? 'N/A' }}</td>
                    <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                    <td style="text-align: right;">{{ $barang->stok }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4"><strong>Total Stok Seluruh Barang</strong></td>
                    <td colspan="1" style="text-align: right;"><strong>{{ $totalStokSemuaBarang }}</strong></td>
                </tr> --}}
            @foreach($allBarangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->jenisBarang->nama_jenis_barang ?? 'N/A' }}</td>
                    <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                    <td style="text-align: right;">{{ $barang->stok }}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="4"><strong>Total Stok Seluruh Barang</strong></td>
                    <td colspan="1" style="text-align: right;"><strong>{{ $totalStokSemuaBarang }}</strong></td>
                </tr> 
        </tbody>
    </table>

    {{-- <h3>Total Stok: {{ $totalStokSemuaBarang }}</h3> --}}
</body>
</html>
