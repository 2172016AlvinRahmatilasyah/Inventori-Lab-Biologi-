<!DOCTYPE html>
<html>
<head>
    <title>Laporan Total Stok</title>
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
        h1{
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
                <th>Id</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Satuan Stok</th>
                <th>Stok</th>
                {{-- <th>Kadaluarsa</th>
                <th>Lokasi</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($allBarangs as $barang)
                <tr>
                    <td>{{ $loop->iteration + (($allBarangs->currentPage() - 1) * $allBarangs->perPage()) }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->jenisBarang->nama_jenis_barang ?? 'N/A' }}</td>
                    <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                    <td style="text-align: right;">{{ $barang->stok }}</td>
                    {{-- <td>{{ $barang->kadaluarsa }}</td>
                    <td>{{ $barang->lokasi }}</td> --}}
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
