<?php

use App\Models\supkonpro;
use App\Models\PenerimaanBarang;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\SupkonproController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\PenerimaanBarangController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
    // Route::get('detail-barang/{id}', [JenisBarangController::class, 'show'])->name('barang.detail');

    Route::get('kelola-barang', [BarangController::class, 'loadAllBarangs'])->name('kelola-barang');
    Route::get('barang-search', [BarangController::class, 'search'])->name('barangs.search');
    Route::get('barangs', [BarangController::class, 'loadAllBarangs']);
    Route::get('add-barang', [BarangController::class, 'loadAddBarangForm']);
    Route::post('add-barang', [BarangController::class, 'AddBarang'])->name('AddBarang');
    Route::get('edit-barang/{id}', [BarangController::class, 'loadEditForm']);
    Route::put('edit-barang', [BarangController::class, 'EditBarang'])->name('EditBarang');
    Route::get('delete-barang/{id}', [BarangController::class, 'deleteBarang']);

    Route::get('saldo-awal', [SaldoAwalController::class, 'loadAllSaldoAwals'])->name('saldo-awal');
    Route::get('saldo-awal-search', [SaldoAwalController::class, 'search'])->name('saldoawals.search');
    Route::get('saldoawals', [SaldoAwalController::class, 'loadAllSaldoAwals']);
    Route::get('add-saldo-awal', [SaldoAwalController::class, 'loadAddSaldoAwalForm']);
    Route::post('add-saldo-awal', [SaldoAwalController::class, 'AddSaldoAwal'])->name('AddSaldoAwal');
   
    // Route::get('/supkonpro/{jenis}', [supkonpro::class, 'handleType']);
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
    Route::get('master-barang-masuk', [PenerimaanBarangController::class, 'loadAllMasterPenerimaanBarang'])->name('master-barang-masuk');
    Route::get('master-barang-masuk-search', [PenerimaanBarangController::class, 'MasterBarangMasukSearch'])->name('master-barang-masuk.search');
    Route::get('tambah-barang-masuk', [PenerimaanBarangController::class, 'loadAddBarangMasukForm']);
    Route::post('tambah-barang-masuk', [PenerimaanBarangController::class, 'AddBarangMasuk'])->name('AddBarangMasuk');
    Route::get('delete-penerimaan-barang/{id}', [PenerimaanBarangController::class, 'deleteMasterBarang']);
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
   

});

require __DIR__.'/auth.php';
