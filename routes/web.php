<?php

use App\Models\supkonpro;
use App\Models\PenerimaanBarang;
use App\Models\PengeluaranBarang;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\SupkonproController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PengeluaranBarangController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index', ])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('generate-report', [DashboardController::class, 'generateReport'])
    // ->name('generateReport');
    //laporan barang masuk
    Route::get('laporan-barang-masuk', [DashboardController::class, 'showBarangMasuk'])
        ->name('laporan-barang-masuk');
    Route::get('laporan-barang-masuk-pdf', [DashboardController::class, 'downloadBarangMasukPdf'])
        ->name('laporan-barang-masuk-pdf');
    //laporan barang keluar
    Route::get('laporan-barang-keluar', [DashboardController::class, 'showBarangKeluar'])
                ->name('laporan-barang-keluar');
    Route::get('laporan-barang-keluar-pdf', [DashboardController::class, 'downloadBarangKeluarPdf'])
                ->name('laporan-barang-keluar-pdf');
    //laporan perubahan persediaan
    Route::get('/laporan-perubahan-persediaan', [DashboardController::class, 
                'showPerubahanPersediaan'])->name('laporan-perubahan-persediaan');
    Route::get('laporan-perubahan-persediaan-pdf', [DashboardController::class, 
                'downloadPerubahanPersediaanPdf'])->name('laporan-perubahan-persediaan-pdf');
    //laporan stok minimum
    Route::get('laporan-stok-minimum', [DashboardController::class, 'showBarangStokMinimal'])
                ->name('laporan-stok-minimum');
    Route::get('laporan-stok-minimum-pdf', [DashboardController::class, 'downloadBarangStokMinimalPdf'])
        ->name('laporan-stok-minimum-pdf');
    //laporan mendekati kadaluarsa
    Route::get('laporan-mendekati-kadaluarsa', [DashboardController::class, 
                'showKadaluarsa'])->name('laporan-mendekati-kadaluarsa');
    Route::get('laporan-mendekati-kadaluarsa-pdf', [DashboardController::class, 
                'downloadKadaluarsaPdf'])->name('laporan-mendekati-kadaluarsa-pdf');
    //laporan total stok
    Route::get('/laporan-total-stok', [DashboardController::class, 'showTotalStok'])
                ->name('laporan-total-stok');
    Route::get('laporan-total-stok-pdf', [DashboardController::class, 
                'downloadTotalStokPdf'])->name('laporan-total-stok-pdf');
    //laporan saldo awal
    // Route::get('/laporan-saldo/{type}', [DashboardController::class, 'showSaldo'])->name('laporan-saldo');
    // Route::get('/laporan-saldo-awal-pdf/{type}', [DashboardController::class, 
    //             'downloadSaldoAwalPdf'])->name('laporan-saldo-awal-pdf');
    Route::get('/laporan-saldo', [DashboardController::class, 'showSaldo'])->name('laporan-saldo');
    Route::get('/laporan-saldo-awal-pdf', [DashboardController::class, 
                'downloadSaldoAwalPdf'])->name('laporan-saldo-awal-pdf');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('kelola-jenis-barang', [JenisBarangController::class, 'loadAllJenisBarangs'])->name('kelola-jenis-barang');
    Route::get('jenis-barang-search', [JenisBarangController::class, 'search'])->name('jenis-barangs.search');
    Route::get('jenis-barangs', [JenisBarangController::class, 'loadAllJenisBarangs']);
    Route::get('add-jenis-barang', [JenisBarangController::class, 'loadAddJenisBarangForm']);
    Route::post('add-jenis-barang', [JenisBarangController::class, 'AddJenisBarang'])->name('AddJenisBarang');
    Route::get('edit-jenis-barang/{id}', [JenisBarangController::class, 'loadEditForm']);
    Route::put('edit-jenis-barang', [JenisBarangController::class, 'EditJenisBarang'])->name('EditJenisBarang');
    Route::get('delete-jenis-barang/{id}', [JenisBarangController::class, 'deleteJenisBarang']);
    Route::get('detail-jenis-barang/{id}', [JenisBarangController::class, 'detailJenisBarang'])->name('detail-jenis-barang');

    Route::get('kelola-barang', [BarangController::class, 'loadAllBarangs'])->name('kelola-barang');
    Route::get('barang-search', [BarangController::class, 'search'])->name('barangs.search');
    Route::get('barangs', [BarangController::class, 'loadAllBarangs']);
    Route::get('add-barang', [BarangController::class, 'loadAddBarangForm']);
    Route::post('add-barang', [BarangController::class, 'AddBarang'])->name('AddBarang');
    Route::get('edit-barang/{id}', [BarangController::class, 'loadEditForm']);
    Route::put('edit-barang', [BarangController::class, 'EditBarang'])->name('EditBarang');
    Route::get('delete-barang/{id}', [BarangController::class, 'deleteBarang']);
    Route::get('detail-barang/{id}', [BarangController::class, 'detailTransaksiBarang'])
               ->name('barang.detail');

    Route::get('saldo-awal', [SaldoAwalController::class, 'loadAllSaldoAwals'])->name('saldo-awal');
    Route::get('saldo-awal-search', [SaldoAwalController::class, 'search'])->name('saldoawals.search');
    Route::get('saldoawals', [SaldoAwalController::class, 'loadAllSaldoAwals']);
    Route::get('add-saldo-awal', [SaldoAwalController::class, 'loadAddSaldoAwalForm']);
    Route::post('add-saldo-awal', [SaldoAwalController::class, 'AddSaldoAwal'])->name('AddSaldoAwal');
    Route::get('get-saldo-awal-dan-transaksi', [SaldoAwalController::class, 'getSaldoAwalDanTransaksi'])->name('getSaldoAwalDanTransaksi');
    Route::get('/detail-saldo-awal/{barang_id}', [SaldoAwalController::class, 'detailSaldoAwal'])->name('detailSaldoAwal');
    
    Route::get('supkonpro/{jenis}', [SupkonproController::class, 'loadAllSupkonpros'])->name('supkonpro');
    Route::get('supkonpro-search/{jenis}', [SupkonproController::class, 'search'])->name('supkonpros.search');
    Route::get('supkonpros/{jenis}', [SupkonproController::class, 'loadAllSupkonpros']);
    Route::get('add-{jenis}', [SupkonproController::class, 'loadAddSupkonproForm']);
    Route::post('add-{jenis}', [SupkonproController::class, 'AddSupkonpro'])->name('AddSupkonpro');
    Route::get('edit-supkonpro/{id}/{jenis}', [SupkonproController::class, 'loadEditForm'])->name('edit-supkonpro');
    Route::put('edit-supkonpro/{id}/{jenis}', [SupkonproController::class, 'EditSupkonpro'])->name('EditSupkonpro');
    Route::get('delete-supkonpro/{id}/{jenis}', [SupkonproController::class, 'deleteSupkonpro'])->name('supkonpro.delete');

    Route::get('kelola-user/{role}', [UserController::class, 'loadAllUsers'])->name('user');
    Route::get('kelola-user-search/{role}', [UserController::class, 'search'])->name('users.search');
    Route::get('kelola-users/{role}', [UserController::class, 'loadAllUsers']);
    Route::get('kelola-user-add-{role}', [UserController::class, 'loadAddUserForm']);
    Route::post('kelola-user-add-{role}', [UserController::class, 'AddUser'])->name('AddUser');
    Route::get('edit-kelola-user/{id}/{role}', [UserController::class, 'loadEditForm'])->name('edit-user');
    Route::put('edit-kelola-user/{id}/{role}', [UserController::class, 'EditUser'])->name('EditUser');
    Route::get('delete-kelola-user/{id}/{role}', [UserController::class, 'deleteUser'])->name('user.delete');

    //master barang masuk:
    Route::get('master-barang-masuk', [PenerimaanBarangController::class, 'loadAllPenerimaanBarang'])->name('master-barang-masuk');
    Route::get('master-barang-masuk-search', [PenerimaanBarangController::class, 'BarangMasukSearch'])->name('master-barang-masuk.search');
    Route::get('tambah-barang-masuk', [PenerimaanBarangController::class, 'loadAddBarangMasukForm']);
    Route::post('tambah-barang-masuk', [PenerimaanBarangController::class, 'AddBarangMasuk'])->name('AddBarangMasuk');
    // Route::get('delete-penerimaan-barang/{id}', [PenerimaanBarangController::class, 'deleteMasterBarang']);
    Route::get('edit-penerimaan-barang/{id}', [PenerimaanBarangController::class, 'loadEditBarangMasukForm']);
    Route::put('edit-penerimaan-barang/{id}', [PenerimaanBarangController::class, 'EditPenerimaanBarang'])
                ->name('EditPenerimaanBarang');
    Route::get('/generate-invoice-penerimaan', [PenerimaanBarangController::class, 'generateInvoicePenerimaan'])
                ->name('generateInvoicePenerimaan');
    Route::get('delete-detail-penerimaan-barang/{id}', [PenerimaanBarangController::class, 
                'deletePenerimaanBarang'])->name('deletePenerimaanBarang');
    //detail barang masuk:
    Route::get('detail-penerimaan-barang/{id}', [PenerimaanBarangController::class, 'detailMasterBarang'])->name('detail-penerimaan-barang');
    Route::get('index-detail-barang-masuk', [PenerimaanBarangController::class, 'loadAllDetailPenerimaanBarang'])->name('index-detail-barang-masuk');
    Route::get('detail-barang-masuk-search', [PenerimaanBarangController::class, 'DetailBarangMasukSearch'])->name('detail-barang-masuk.search');
    //jenis barang masuk:
    Route::get('jenis-barang-masuk', [PenerimaanBarangController::class, 'loadAllJenisPenerimaanBarang'])->name('jenis-barang-masuk');
    Route::get('tambah-jenis-barang-masuk', [PenerimaanBarangController::class, 'loadAddJenisBarangMasukForm']);
    Route::post('tambah-jenis-barang-masuk', [PenerimaanBarangController::class, 'AddJenisBarangMasuk'])->name('AddJenisBarangMasuk');
    Route::get('delete-jenis-barang-masuk/{id}', [PenerimaanBarangController::class, 'deleteJenisBarangMasuk']);
    Route::get('edit-jenis-barang-masuk/{id}', [PenerimaanBarangController::class, 'loadEditJenisBarangMasukForm']);
    Route::put('edit-jenis-barang-masuk', [PenerimaanBarangController::class, 'EditJenisBarangMasuk'])->name('EditJenisBarangMasuk');
   

    //master barang keluar:
    Route::get('master-barang-keluar', [PengeluaranBarangController::class, 'loadAllPengeluaranBarang'])
               ->name('master-barang-keluar');
    Route::get('master-barang-keluar-search', [PengeluaranBarangController::class, 'BarangKeluarSearch'])
               ->name('master-barang-keluar.search');
    Route::get('tambah-barang-keluar', [PengeluaranBarangController::class, 'loadAddBarangKeluarForm']);
    Route::post('tambah-barang-keluar', [PengeluaranBarangController::class, 'AddBarangKeluar'])->name('AddBarangKeluar');
    // Route::get('delete-pengeluaran-barang/{id}', [PengeluaranBarangController::class, 'deletePengeluaranBarang']);
    Route::get('edit-pengeluaran-barang/{id}', [PengeluaranBarangController::class, 'loadEditBarangKeluarForm']);
    Route::put('edit-pengeluaran-barang/{id}', [PengeluaranBarangController::class, 'EditPengeluaranBarang'])
                ->name('EditPengeluaranBarang');
    Route::get('/generate-invoice-pengeluaran', [PengeluaranBarangController::class, 'generateInvoicePengeluaran'])
                ->name('generateInvoicePengeluaran');
    Route::get('delete-detail-pengeluaran-barang/{id}', [PengeluaranBarangController::class, 
                'deletePengeluaranBarang'])->name('deletePengeluaranBarang');
    //detail barang keluar:
    Route::get('detail-pengeluaran-barang/{id}', [PengeluaranBarangController::class, 'detailPengeluaranBarang'])
               ->name('detail-pengeluaran-barang');
    Route::get('index-detail-barang-keluar', [PengeluaranBarangController::class, 'loadAllDetailPengeluaranBarang'])
               ->name('index-detail-barang-keluar');
    Route::get('detail-barang-keluar-search', [PengeluaranBarangController::class, 'DetailBarangKeluarSearch'])
               ->name('detail-barang-keluar.search');
    //jenis barang keluar:
    Route::get('jenis-barang-keluar', [PengeluaranBarangController::class, 'loadAllJenisPengeluaranBarang'])
               ->name('jenis-barang-keluar');
    Route::get('tambah-jenis-barang-keluar', [PengeluaranBarangController::class, 'loadAddJenisBarangKeluarForm']);
    Route::post('tambah-jenis-barang-keluar', [PengeluaranBarangController::class, 'AddJenisBarangKeluar'])
                ->name('AddJenisBarangKeluar');
    Route::get('delete-jenis-barang-keluar/{id}', [PengeluaranBarangController::class, 'deleteJenisBarangKeluar']);
    Route::get('edit-jenis-barang-keluar/{id}', [PengeluaranBarangController::class, 'loadEditJenisBarangKeluarForm']);
    Route::put('edit-jenis-barang-keluar', [PengeluaranBarangController::class, 'EditJenisBarangKeluar'])
               ->name('EditJenisBarangKeluar');

});

require __DIR__.'/auth.php';
