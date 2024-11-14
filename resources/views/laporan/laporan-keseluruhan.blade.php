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
    <p>Saldo Awal Keseluruhan Barang Bulan Ini: Rp {{ number_format($totalSaldoAwalBulanIni, 0, ',', '.') }}</p>
    <p>Saldo Terima Keseluruhan Barang Bulan Ini: Rp {{ number_format($totalSaldoTerimaBulanIni, 0, ',', '.') }}</p>
    <p>Saldo Keluar Keseluruhan Barang Bulan Ini: Rp {{ number_format($totalSaldoKeluarBulanIni, 0, ',', '.') }}</p>
    
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
                    <td colspan="13">Tidak Ada Transaksi Barang Masuk Bulan Ini!</td>
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
                    <td colspan="13">Tidak Ada Transaksi Barang Masuk Bulan Ini!</td>
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
            @if(count($barangList) > 0)
                @foreach ($barangList as $barang)
                    <tr>
                        <td>{{ $barang['nama_barang'] }}</td>
                        <td>{{ $barang['satuan_stok'] }}</td>
                        <td>{{ $barang['jumlah_keluar'] }}</td>
                        <td>{{ $barang['jumlah_masuk'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">Tidak Ada Perubahan Persediaan!</td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>


    <h3>Stok Mendekati/Sudah Minimum</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <tr>
            <th>Nama Barang</th>
            <th>Satuan Stok Barang</th>
            <th>Stok</th>
        </tr>
        @if(count($barangStokMinimal) > 0)
            @foreach($barangStokMinimal as $barang)
                <tr>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->jenisBarang->satuan_stok ?? 'N/A' }}</td>
                    <td>{{ $barang->stok }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3">Tidak Ada Stok Mendekati/Sudah Minimum!</td>
            </tr>
        @endif
    </table>
    <br>
    


    <h3>Barang Mendekati Kadaluarsa</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <tr>
            <th>Nama Barang</th>
            <th>Kadaluarsa</th>
        </tr>
        @if(count($barangKadaluarsaMendekati) > 0)
            @foreach($barangKadaluarsaMendekati as $barang)
                <tr>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kadaluarsa }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2">Tidak Ada Barang Mendekati Kadaluarsa!</td> 
            </tr>
        @endif
    </table>
    <br>
    

    <h3>Total Barang Keseluruhan</h3>
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
                @php
                    $totalStokSemuaBarang = 0;
                @endphp
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
                    @php
                        $totalStokSemuaBarang += $barang->stok;
                    @endphp
                @endforeach
                <!-- Baris untuk total stok -->
                <tr>
                    <td colspan="3"><strong>Total Stok Seluruh Barang</strong></td>
                    <td colspan="6"><strong>{{ $totalStokSemuaBarang }}</strong></td>
                </tr>
            @else
                <tr>
                    <td colspan="9">Barang tidak ditemukan!</td>
                </tr>
            @endif
        </tbody>
    </table>

    <br>

    <h3>Saldo Awal Bulan Ini</h3>
    <table class="table table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Saldo Awal</th>
                <th>Total Terima</th>
                <th>Total Keluar</th>
                <th>Saldo Akhir</th>
                <th>Tanggal Ditambah</th>
                <th>Tanggal Diupdate</th>
            </tr>
        </thead>
        <tbody>
            @if($all_saldo_awals->isNotEmpty())
                @foreach ($all_saldo_awals as $index => $saldo_awal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $saldo_awal->barang->nama_barang ?? 'N/A' }}</td>
                        <td>{{ $saldo_awal->tahun }}</td>
                        <td>{{ $saldo_awal->bulan }}</td>
                        <td>{{ number_format($saldo_awal->saldo_awal, 0, ',', '.') }}</td>
                        <td>{{ number_format($saldo_awal->total_terima, 0, ',', '.') }}</td>
                        <td>{{ number_format($saldo_awal->total_keluar, 0, ',', '.') }}</td>
                        <td>{{ number_format($saldo_awal->saldo_akhir, 0, ',', '.') }}</td>
                        <td>{{ $saldo_awal->created_at }}</td>
                        <td>{{ $saldo_awal->updated_at }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">Data tidak ditemukan!</td>
                </tr>
            @endif
        </tbody>
    </table>
    

</body>
</html>