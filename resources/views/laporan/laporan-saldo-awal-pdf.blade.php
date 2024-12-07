<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $type }}</title>
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
    <h1>Laporan {{ $type }}</h1>
    <br>
    
    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <p>Berdasarkan: {{ $filter }}</p>
    <p>Tanggal: {{ $startDate }} - {{ $endDate }}</p>
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
                            <td style="text-align: right;">
                                @if($type === 'saldo-awal')
                                    {{ $saldo_awal->saldo_awal, 0 }}
                                @elseif($type === 'saldo-terima')
                                    {{ $saldo_awal->total_terima, 0 }}
                                @elseif($type === 'saldo-keluar')
                                    {{$saldo_awal->total_keluar, 0,  }}
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