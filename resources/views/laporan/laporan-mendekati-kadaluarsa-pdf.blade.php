<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mendekati Kadaluarsa</title>
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
    <h1>Laporan Mendekati Kadaluarsa</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <br>
    
    <h3>Data Barang Mendekati Kadaluarsa</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Kadaluarsa</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($barangKadaluarsaMendekati) && count($barangKadaluarsaMendekati) > 0)
                    @foreach ($barangKadaluarsaMendekati as $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->kadaluarsa }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">Tidak Ada Barang Mendekati Kadaluarsa!</td> 
                    </tr>
                @endif
            </tbody>
    </table>
</body>
</html>