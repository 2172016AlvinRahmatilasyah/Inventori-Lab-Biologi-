<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $type }}</title>
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
    <h1>Laporan {{ $type }}</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <br>
    
    <h3>Data {{ $type }}</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>{{ $type }}</th>
                    <th>Tanggal Ditambah</th>
                    <th>Tanggal Diupdate</th>
                </tr>
            </thead>
            <tbody>
                @if($allSaldoAwals && $allSaldoAwals->count() > 0)
                    @foreach ($allSaldoAwals as $saldo_awal)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $saldo_awal->barang->nama_barang ?? 'N/A' }}</td>
                            <td>{{ $saldo_awal->tahun }}</td>
                            <td>{{ $saldo_awal->bulan }}</td>
                            <td>
                                @if($type === 'saldo-awal')
                                    {{ number_format($saldo_awal->saldo_awal, 0, ',', '.') }}
                                @elseif($type === 'saldo-terima')
                                    {{ number_format($saldo_awal->total_terima, 0, ',', '.') }}
                                @elseif($type === 'saldo-keluar')
                                    {{ number_format($saldo_awal->total_keluar, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>{{ $saldo_awal->created_at }}</td>
                            <td>{{ $saldo_awal->updated_at }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">Data tidak ditemukan!</td>
                    </tr>
                @endif
            </tbody>
    </table>
</body>
</html>