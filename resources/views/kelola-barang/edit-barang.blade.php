<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Jenis Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header text-center">Edit Jenis Barang</div>
            @if (Session::has('fail'))
                <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
            @endif
            <div class="card-body">
                <form action="{{ route('EditJenisBarang') }}" method="post">
                    @csrf
                    <input type="hidden" name="jenis_barang_id" value="{{ $jenis_barang->id }}">
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Nama Jenis Barang</label>
                        <input type="text" name="nama_jenis_barang" value="{{ $jenis_barang->nama_jenis_barang }}" class="form-control" id="formGroupExampleInput" placeholder="Enter Nama jenis barang">
                        @error('nama_jenis_barang')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Satuan Stok</label>
                        <input type="text" name="satuan_stok" value="{{ $jenis_barang->satuan_stok }}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Satuan Stok">
                        @error('satuan_stok')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
