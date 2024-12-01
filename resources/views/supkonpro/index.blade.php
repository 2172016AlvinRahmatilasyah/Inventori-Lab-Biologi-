@extends('layouts.app')

@section('title', 'Kelola ' . $jenis)

@section('content')
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script>

    <!-- Tempatkan konten khusus halaman dashboard di sini -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h2>Daftar {{ $jenis }}</h2>
                <a href="/add-{{ strtolower($jenis) }}" class="btn btn-success btn-sm ml-auto">Tambah 
                    {{ $jenis }} Baru</a>
            </div>

            {{-- Flash message for success or failure --}}
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if(Session::has('fail'))
                <div class="alert alert-danger">
                    {{ Session::get('fail') }}
                </div>
            @endif

            <div class="mb-3">
                <form action="{{ route('supkonpros.search', ['jenis' => strtolower($jenis)]) }}" 
                      method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" 
                           placeholder="Search here" value="{{ request()->get('query') }}">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('supkonpro', ['jenis' => strtolower($jenis)]) }}" class="btn btn-secondary ml-3 ">Reset</a>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                                <th colspan="3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($all_supkonpros) && count($all_supkonpros) > 0)
                                @foreach ($all_supkonpros as $supkonpro)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supkonpro->nama }}</td>
                                        <td>{{ $supkonpro->alamat }}</td>
                                        <td>{{ $supkonpro->kota }}</td>
                                        <td>{{ $supkonpro->telepon}}</td>
                                        <td>{{ $supkonpro->email }}</td>
                                        <td>{{ $supkonpro->status }}</td>
                                        <td>{{ $supkonpro->created_at }}</td>
                                        <td>{{ $supkonpro->updated_at }}</td>
                                        {{-- <td><a href="/edit-{{ strtolower($jenis) }}/{{ $supkonpro->id }}" class="btn btn-primary btn-sm">Edit</a></td> --}}
                                        <td>
                                            <a href="{{ route('edit-supkonpro', ['id' => $supkonpro->id, 'jenis' => strtolower($jenis)]) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('supkonpro.delete', ['id' => $supkonpro->id,
                                               'jenis' => strtolower($jenis)]) }}" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure?')">Delete
                                            </a>
                                        </td>
                                        {{-- <td>
                                            <a href="{{ route('EditSupkonpro', ['id' => $supkonpro->id, 'jenis' => strtolower($jenis)]) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">Data tidak ditemukan !</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    
@endsection
