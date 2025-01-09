@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')

@section('content')
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/js/demo/datatables-demo.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header text-center">Tambah Barang Keluar</div>
        @if (Session::has('fail'))
            <span class="alert alert-danger p-2">{{ Session::get('fail') }}</span>
        @endif
        <div class="card-body">
            <form action="{{ route('AddBarangKeluar') }}" method="post" id="barangKeluarForm">
                @csrf
                <!-- Input Tanggal -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" 
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="invoice" class="form-label">Invoice</label>
                    <input type="text" name="invoice" id="invoice" class="form-control" readonly required>
                </div>

                <!-- Input Jenis Barang Keluar -->
                <div class="mb-3">
                    <label for="jenis_id" class="form-label">Jenis Barang Keluar</label>
                    <select name="jenis_id" class="form-control select2" id="jenis_id" required>
                        <option value="">Pilih Jenis Barang Keluar</option>
                        @foreach ($all_jenis_pengeluarans as $jenis_pengeluaran)
                            <option value="{{ $jenis_pengeluaran->id }}" data-jenis="{{ $jenis_pengeluaran->jenis }}">
                                {{ $jenis_pengeluaran->jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <!-- SupKonPro -->
                <div class="mb-3" id="supkonpro-container">
                    <label for="supkonpro_id" class="form-label">Supplier/Konsumen/Proyek</label>
                    <select name="supkonpro_id" class="form-control select2" id="supkonpro_id" required>
                        <option value="">Pilih Jenis Supplier/Konsumen/Proyek</option>
                        <option value="0">Tidak Ada</option>
                        @foreach ($all_supkonpros as $supkonpro)
                            <option value="{{ $supkonpro->id }}" data-jenis="{{ $supkonpro->jenis }}">
                                {{ $supkonpro->jenis }} {{ $supkonpro->nama }}
                            </option>
                        @endforeach
                    </select>                          
                    @error('supkonpro_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label">Nama</label>
                    <select class="form-control" disabled required>
                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    </select>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    @error('user_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="nama_pengambil" class="form-label">Nama Pengambil</label>
                    <input type="text" name="nama_pengambil" id="nama_pengambil" 
                            value="{{ old('nama_pengambil') }}" class="form-control" 
                            placeholder="Enter Nama Pengambil" required>
                    @error('nama_pengambil')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Dynamic Barang Inputs -->
                {{-- <div id="barang-container">
                    <div class="barang-entry mb-3">
                        <label for="barang_id" class="form-label">Nama Barang</label>
                        <select name="barang_id[]" class="form-control select2 barang-select">
                            <option value="">Pilih Nama Barang</option>
                            @foreach ($all_barangs as $barang)
                                <option value="{{ $barang->id }}">
                                    Nama Barang:{{ $barang->nama_barang }} - 
                                    Lokasi: {{ $barang->lokasi }} - 
                                    Stok: {{ $barang->stok}}
                                </option>
                            @endforeach
                        </select>
                        <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                        <input step="0.01" type="number" name="jumlah_keluar[]" class="form-control jumlah-keluar" placeholder="Enter jumlah">
                
                        <label for="harga" class="form-label">Harga</label>
                        <input step="0.01" type="number" name="harga[]" class="form-control harga" placeholder="Enter harga">
                
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input step="0.01" type="number" name="total_harga[]" class="form-control total-harga" readonly placeholder="Enter total harga">
                    </div>
                </div> --}}
                <div id="barang-container"></div>
        
                <button type="button" id="add-barang-btn" class="btn btn-success mb-3">+ Barang</button>
                
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" 
                           value="{{ old('keterangan') }}" class="form-control" 
                           placeholder="Enter keterangan" required>
                    @error('keterangan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="harga_invoice" class="form-label">Harga Invoice</label>
                    <input type="text" name="harga_invoice" id="harga_invoice" 
                           value="{{ old('harga_invoice') }}" class="form-control" 
                           placeholder="Enter Harga Invoice" readonly required>
                    @error('harga_invoice')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>                
                <button type="button" id="openConfirmationModal" class="btn btn-primary w-100" style="display: none;">Save</button>
                <p style="color: red;">*Tombol Save otomatis akan muncul jika sudah mengisi semua data</p>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).on('change', '#jenis_id', function () {
        const jenisId = $(this).val();
        if (jenisId === '0') {
            $('#supkonpro_id').val('0').trigger('change'); // Set supkonpro_id ke 0
            $('#supkonpro_id').prop('disabled', true); // Nonaktifkan dropdown
        } else {
            $('#supkonpro_id').prop('disabled', false); // Aktifkan dropdown kembali
            $('#supkonpro_id').val('').trigger('change'); // Reset nilai jika bukan 0
        }
    });

    $(document).ready(function() {
        // Inisialisasi Select2 untuk elemen awal
        $('.select2').select2();

        function validateLabels() {
            // Periksa semua label dalam form
            $('form#barangKeluarForm .form-label').each(function () {
                const associatedInput = $(this).siblings('input, select');
                
                if (associatedInput.length > 0) {
                    if (associatedInput.prop('readonly')) {
                        $(this).css('color', 'black'); // Tetap hitam untuk input readonly
                    } else if (associatedInput.val() === '' || associatedInput.val() === null) {
                        $(this).css('color', 'red'); // Ubah warna label menjadi merah
                    } else {
                        $(this).css('color', 'black'); // Ubah warna label menjadi hitam
                    }
                }
            });
            
            $('#barang-container .barang-entry').each(function () {
                const jumlahKeluarLabel = $(this).find('label[for="jumlah_keluar"]');
                const jumlahKeluarInput = $(this).find('input[name="jumlah_keluar[]"]');
                const hargaLabel = $(this).find('label[for="harga"]');
                const hargaInput = $(this).find('input[name="harga[]"]');

                if (jumlahKeluarInput.val() === '' || jumlahKeluarInput.val() === null) {
                    jumlahKeluarLabel.css('color', 'red');
                } else {
                    jumlahKeluarLabel.css('color', 'black');
                }

                // Validasi harga
                if (hargaInput.val() === '' || hargaInput.val() === null) {
                    hargaLabel.css('color', 'red');
                } else {
                    hargaLabel.css('color', 'black');
                }
            });
        }
        // Event listener untuk setiap input dan select
        $('#barangKeluarForm').on('input change', 'input, select', function () {
            validateLabels(); // Panggil fungsi validasi label
        });

        // Validasi saat halaman dimuat
        validateLabels();

        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'), {
            keyboard: false,
            backdrop: 'static',
        });

        // Event Listener untuk Tombol Save
        $('#openConfirmationModal').on('click', function (e) {
            e.preventDefault(); // Mencegah aksi default
            confirmationModal.show(); // Menampilkan modal konfirmasi
        });

        // Event Listener untuk Tombol Edit (Tutup Modal)
        $('#confirmationModal .btn-secondary').on('click', function () {
            confirmationModal.hide(); // Menutup modal
        });

        // Event Listener untuk Tombol OK (Submit Form)
        $('#confirmSaveBtn').on('click', function () {
            $('#barangKeluarForm').submit(); // Submit form
        });
        
        // Fungsi untuk menghitung total harga per barang
        function calculateTotalHarga() {
            $('#barang-container .barang-entry').each(function () {
                const jumlahKeluar = parseFloat($(this).find('.jumlah-keluar').val()) || 0;
                const harga = parseFloat($(this).find('.harga').val()) || 0;
                const totalHarga = jumlahKeluar * harga;

                // Update input Total Harga
                $(this).find('.total-harga').val(totalHarga.toFixed(2)); // Format dua desimal
            });

            // Panggil fungsi untuk menghitung total invoice
            calculateHargaInvoice();
        }

        // Fungsi untuk menghitung harga invoice (total keseluruhan)
        function calculateHargaInvoice() {
            let totalInvoice = 0;

            // Iterasi setiap entri barang untuk menjumlahkan total harga
            $('#barang-container .barang-entry').each(function () {
                const totalHarga = parseFloat($(this).find('.total-harga').val()) || 0;
                totalInvoice += totalHarga;
            });

            // Update input Harga Invoice
            $('#harga_invoice').val(totalInvoice.toFixed(2)); // Format dua desimal
        }

        // Tambahkan barang baru
        $('#add-barang-btn').click(function () {
            var newBarangEntry = `
                <div class="barang-entry mb-3">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select name="barang_id[]" class="form-control select2 barang-select" required>
                        <option value="">Pilih Nama Barang</option>
                        @foreach ($all_barangs as $barang)
                            <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}">
                                Barang {{ $barang->nama_barang }}, Lokasi {{ $barang->lokasi }}, Stok {{ $barang->stok }}
                            </option>
                        @endforeach
                    </select>
                    <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                    <input step="0.01" type="number" name="jumlah_keluar[]" class="form-control jumlah-keluar" placeholder="Enter jumlah" required>
                    <label for="harga" class="form-label">Harga</label>
                    <input step="0.01" type="number" name="harga[]" class="form-control harga" placeholder="Enter harga" required>
                    <label for="total_harga" class="form-label">Total Harga</label>
                    <input step="0.01" type="number" name="total_harga[]" class="form-control total-harga" readonly placeholder="Enter total harga" required>
                    <button type="button" class="btn btn-danger mt-2 remove-barang-btn">Batal</button>
                </div>
            `;

            // Tambahkan elemen baru ke DOM
            $('#barang-container').append(newBarangEntry);
            $('.select2').select2(); // Inisialisasi ulang Select2 untuk elemen baru
            validateLabels(); // Validasi label untuk elemen baru
        });

        $(document).on('click', '.remove-barang-btn', function () {
            const $barangEntry = $(this).closest('.barang-entry'); // Ambil elemen barang-entry terkait

            // Tampilkan dialog konfirmasi
            const userConfirmed = confirm('Apakah Anda yakin ingin menghapus data ini? Pilih "OK" untuk menghapus atau "Cancel" untuk batal.');

            if (userConfirmed) {
                // Hapus data jika pengguna mengonfirmasi
                $barangEntry.remove();
                calculateTotalHarga(); // Hitung ulang total harga setelah barang dihapus
                validateForm(); // Validasi ulang setelah barang dihapus
            }
            // Jika pengguna memilih batal, tidak ada yang terjadi dan data tetap ada
        });

        // Event listener untuk perhitungan otomatis
        $(document).on('input', '.jumlah-keluar, .harga', function () {
            calculateTotalHarga(); // Hitung ulang total harga per barang dan total invoice
        });

        // Validasi form
        function validateForm() {
            let isValid = true;

            // Validasi semua input dan select yang memiliki atribut required
            $('#barangKeluarForm').find('input[required], select[required]').each(function () {
                if ($(this).val() === '' || $(this).val() === null) {
                    isValid = false; // Jika ada yang kosong, form tidak valid
                }
            });

            // Validasi khusus untuk barang di dalam #barang-container
            $('#barang-container .barang-entry').each(function () {
                const barangId = $(this).find('select[name="barang_id[]"]').val();
                const jumlahKeluar = $(this).find('input[name="jumlah_keluar[]"]').val();
                const harga = $(this).find('input[name="harga[]"]').val();

                // Jika salah satu input barang kosong, form tidak valid
                if (!barangId || !jumlahKeluar || !harga) {
                    isValid = false;
                }
            });

            // Tampilkan atau sembunyikan tombol Save
            if (isValid) {
                $('#openConfirmationModal').show(); // Tampilkan tombol Save
            } else {
                $('#openConfirmationModal').hide(); // Sembunyikan tombol Save
            }
        }

        // Validasi form setiap input berubah
        $('#barangKeluarForm').on('input change', 'input, select', function () {
            validateForm();
        });

        // Validasi form saat halaman dimuat
        validateForm();
        
        $(document).on('change', '.barang-select', function () {
            const selectedOption = $(this).find(':selected');
            const barangId = selectedOption.val();
            const stok = selectedOption.data('stok'); // Ambil data stok dari atribut data

            const jumlahKeluarInput = $(this).closest('.barang-entry').find('.jumlah-keluar');

            // Jika jenis barang keluar memiliki id = 0, set jumlah_keluar = stok
            const jenisId = $('#jenis_id').val();
            if (jenisId === '0' && barangId) {
                jumlahKeluarInput.val(stok); // Set jumlah keluar ke stok
                jumlahKeluarInput.prop('readonly', true); // Buat readonly
            } else {
                jumlahKeluarInput.prop('readonly', false); // Biarkan editable jika bukan id = 0
            }

            calculateTotalHarga(); // Hitung ulang total harga
        });

    });

    document.addEventListener('DOMContentLoaded', function () {
        const inputTanggal = document.getElementById('tanggal');
        const inputInvoice = document.getElementById('invoice');

        // Fungsi untuk memperbarui nomor invoice
        function updateInvoice(tanggal) {
            if (tanggal) {
                $.ajax({
                    url: "{{ route('generateInvoicePenerimaan') }}", // Rute backend
                    method: "GET",
                    data: { tanggal: tanggal },
                    success: function (response) {
                        if (response.invoice) {
                            inputInvoice.value = response.invoice; // Isi field invoice
                        } else {
                            console.error("Nomor invoice tidak ditemukan dalam respons.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Terjadi kesalahan saat mengambil nomor invoice:", error);
                    }
                });
            }
        }

        // Tetapkan tanggal hari ini jika tidak ada nilai sebelumnya
        if (!inputTanggal.value) {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            inputTanggal.value = `${year}-${month}-${day}`;
        }

        // Perbarui invoice saat tanggal berubah
        updateInvoice(inputTanggal.value);
        inputTanggal.addEventListener('change', function () {
            updateInvoice(this.value);
        });

        $(document).on('input', '.jumlah-keluar', function () {
            const value = parseFloat($(this).val());
            if (value <= 0 || isNaN(value)) {
                $(this).val(''); // Kosongkan input jika nilainya tidak valid
                alert('Jumlah Keluar harus lebih besar dari 0.');
            }
        });

        $('#barangKeluarForm').on('submit', function (e) {
            let isValid = true;

            $('.jumlah-keluar').each(function () {
                const value = parseFloat($(this).val());
                if (value <= 0 || isNaN(value)) {
                    isValid = false;
                    alert('Jumlah Keluar harus lebih besar dari 0.');
                    return false; // Hentikan iterasi
                }
            });

            if (!isValid) {
                e.preventDefault(); // Batalkan submit form
            }
        });
    });
</script>
@endsection
