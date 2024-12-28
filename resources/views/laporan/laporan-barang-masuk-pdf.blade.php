<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk</title>
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
        tfoot {
            font-weight: bold;
            background-color: #f2f2f2; /* Warna latar belakang untuk pembeda */
        }
        tfoot th {
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Laporan Barang Masuk</h1>
    <br>

    <p>Tanggal laporan dibuat: {{ $date }}</p>
    <p>Pembuat: {{ $user }}</p>
    <p><strong>Filter yang Dipilih:</strong></p>
    <ul>
        <li>Filter Waktu: {{ $filter }}</li>
        <li>Tanggal: {{ $startDate ? $startDate->format('d-m-Y') : '-' }} s/d {{ $endDate ? $endDate->format('d-m-Y') : '-' }}</li>
        <li>Jenis Transaksi: {{ $selectedTransactionType }}</li>
        <li>Supplier/Konsumen/Proyek: {{ $selectedSupkonpro }}</li>
        <li>Barang: {{ $selectedProduct }}</li>
    </ul>
    <br>
    
    <h3>Data Barang Masuk</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                {{-- <th>Id master</th>
                <th>Id detail</th> --}}
                <th>No</th>
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
                <th>Harga Invoice</th>
                {{-- <th>Tanggal Ditambah</th>
                <th>Tanggal Diupdate</th> --}}
            </tr>
        </thead>
        <tbody>
            @if(isset($barangMasuk) && count($barangMasuk) > 0)
            @foreach ($barangMasuk as $item)
                    <tr>
                        {{-- <td>{{ $item->PenerimaanBarang->id ?? 'N/A' }}</td>
                        <td>{{ $item->id }}</td> --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->PenerimaanBarang->invoice ?? 'N/A'}}</td>
                        <td>{{ $item->PenerimaanBarang->tanggal ?? 'N/A'}}</td>
                        <td>{{ $item->PenerimaanBarang->supkonpro->nama ?? 'N/A' }}</td>
                        <td>{{ $item->PenerimaanBarang->user->name ?? 'N/A'}}</td>
                        <td>{{ $item->PenerimaanBarang->jenispenerimaanbarang->jenis  ?? 'N/A' }}</td>
                        <td>{{ $item->PenerimaanBarang->nama_pengantar ?? 'N/A'}}</td>
                        <td>{{ $item->PenerimaanBarang->keterangan ?? 'N/A' }}</td>
                        <td>{{ $item->barang->nama_barang ?? 'N/A'}}</td>
                        <td style="text-align: right;">{{ $item->jumlah_diterima }}</td>
                        <td style="text-align: right;">{{ number_format($item->harga, 0, ',', '.') ?? 'N/A'}}</td>
                        <td style="text-align: right;">{{ number_format($item->total_harga, 0, ',', '.') ?? 'N/A'}}</td>
                        <td style="text-align: right;">{{ number_format($item->penerimaanBarang->harga_invoice, 0, ',', '.') ?? 'N/A'}}</td>
                        {{-- <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td> --}}
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <td colspan="9" style="text-align: right;">Total:</td>
                        <td style="text-align: right;">{{ number_format($totalJumlahDiterima, 2, '.', ',') }}</td>
                        <td></td> <!-- Kosongkan kolom harga -->
                        <td></td> <!-- Kosongkan kolom total harga -->
                        <td style="text-align: right;">{{ number_format($totalHargaInvoice, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>                
            @else
                <tr>
                    <td colspan="13">Tidak Ada Transaksi Barang Masuk!</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>