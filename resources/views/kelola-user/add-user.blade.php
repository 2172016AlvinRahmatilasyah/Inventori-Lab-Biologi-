@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <div class="container">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header text-center">Tambah {{ $role }}</div>
            @if (Session::has('fail'))
                <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
            @endif
            <div class="card-body">
                <form action="{{ route('AddUser', ['role' => $role]) }}" method="post" id="userForm">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                          class="form-control" placeholder="Enter Name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control" disabled>
                            <option value="" disabled selected>Select role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>                        
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                          class="form-control" placeholder="Enter email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">No HP</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" 
                          value="{{ old('phone_number') }}" placeholder="Enter No HP">
                        @error('phone_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" 
                            value="{{ old('password') }}" placeholder="Enter password">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                            value="{{ old('password_confirmation') }}" placeholder="Confirm password">
                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>    
                    <button type="button" id="openConfirmationModal" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Data</h5>
                </div>
                <div class="modal-body">
                    Apakah data yang Anda masukkan sudah yakin?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
                    <button type="button" id="confirmSaveBtn" class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    
            // Handle tombol "Save" untuk membuka modal
            $('#openConfirmationModal').on('click', function () {
                confirmationModal.show(); // Tampilkan modal
            });
    
            // Handle tombol "Edit" untuk menutup modal
            $('#confirmationModal .btn-secondary').on('click', function () {
                confirmationModal.hide(); // Tutup modal
            });
    
            // Handle tombol "OK" untuk submit form
            $('#confirmSaveBtn').on('click', function () {
                $('#userForm').submit(); // Submit form
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var currentUrl = window.location.pathname;
    
            var roleSelect = document.getElementById('role');
    
            // Set role based on URL and disable the select field
            if (currentUrl.includes('/kelola-user-add-user')) {
                roleSelect.value = 'user';
                roleSelect.disabled = true;
            } else if (currentUrl.includes('/kelola-user-add-admin')) {
                roleSelect.value = 'admin';
                roleSelect.disabled = true;
            }
        });
    </script>
@endsection
