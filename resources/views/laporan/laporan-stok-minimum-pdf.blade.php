<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mendekati/Sudah Minimum</title>
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
        h1, h3{
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Stok Mendekati/Sudah Minimum</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <br>
    
    <h3>Data Barang Mendekati/Sudah Minimum</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>No Catalog</th>
                    <th>Jenis Barang</th>
                    <th>Satuan Stok Barang</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @if(count($barangStokMinimal) > 0)
                    @foreach($barangStokMinimal as $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->no_catalog }}</td>
                            <td>{{ $barang->jenisBarang->nama_jenis_barang ?? 'N/A' }}</td>
                            <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                            <td style="text-align: right;">{{ $barang->stok }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">Tidak Ada Stok Mendekati/Sudah Minimum!</td> 
                    </tr>
                @endif
            </tbody>
    </table>
</body>
</html>