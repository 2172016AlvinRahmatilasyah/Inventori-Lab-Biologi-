@extends('layouts.app')

@section('title', 'Kelola ' . $role)

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
                <h2>Daftar {{ $role }}</h2>
                <a href="/kelola-user-add-{{ strtolower($role) }}" class="btn btn-success btn-sm ml-auto">Tambah 
                    {{ $role }} Baru</a>

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
                <form action="{{ route('users.search', ['role' => strtolower($role)]) }}" 
                      method="GET" class="d-flex mt-3">
                    <input type="text" name="query" class="form-control w-50 ml-3" 
                           placeholder="Search here">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                    <a href="{{ route('user', ['role' => strtolower($role)]) }}" class="btn btn-secondary ml-3 ">
                        Reset
                    </a>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Tanggal Ditambah</th>
                                <th>Tanggal Diupdate</th>
                                <th colspan="3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($all_users) && count($all_users) > 0)
                                @foreach ($all_users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('edit-user', ['id' => $user->id, 
                                               'role' => strtolower($role)]) }}" class="btn btn-primary
                                                btn-sm">Edit
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.delete', ['id' => $user->id,
                                               'role' => strtolower($role)]) }}" class="btn btn-danger btn-sm" 
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
