<?php

use App\Models\supkonpro;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\SupkonproController;
use App\Http\Controllers\JenisBarangController;

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
    Route::get('delete-jenis-barang/{id}', [JenisBarangController::class, 'deleteJenisBarang']);
    Route::put('edit-jenis-barang', [JenisBarangController::class, 'EditJenisBarang'])->name('EditJenisBarang');
    // Route::get('detail-barang/{id}', [JenisBarangController::class, 'show'])->name('barang.detail');

    Route::get('kelola-barang', [BarangController::class, 'loadAllBarangs'])->name('kelola-barang');
    Route::get('barang-search', [BarangController::class, 'search'])->name('barangs.search');
    Route::get('barangs', [BarangController::class, 'loadAllBarangs']);
    Route::get('add-barang', [BarangController::class, 'loadAddBarangForm']);
    Route::post('add-barang', [BarangController::class, 'AddBarang'])->name('AddBarang');
    Route::get('edit-barang/{id}', [BarangController::class, 'loadEditForm']);
    Route::get('delete-barang/{id}', [BarangController::class, 'deleteBarang']);
    Route::put('edit-barang', [BarangController::class, 'EditBarang'])->name('EditBarang');

    Route::get('saldo-awal', [SaldoAwalController::class, 'loadAllSaldoAwals'])->name('saldo-awal');
    Route::get('saldo-awal-search', [SaldoAwalController::class, 'search'])->name('saldoawals.search');
    Route::get('saldoawals', [SaldoAwalController::class, 'loadAllSaldoAwals']);
    Route::get('add-saldo-awal', [SaldoAwalController::class, 'loadAddForm']);
    Route::post('add-saldo-awal', [SaldoAwalController::class, 'AddSaldoAwal'])->name('AddSaldoAwal');
   
    // Route::get('/supkonpro/{jenis}', [supkonpro::class, 'handleType']);
    Route::get('supkonpro/{jenis}', [SupkonproController::class, 'loadAllSupkonpros'])->name('supkonpro');
    Route::get('supkonpro-search/{jenis}', [SupkonproController::class, 'search'])->name('supkonpros.search');
    Route::get('supkonpros/{jenis}', [SupkonproController::class, 'loadAllSupkonpros']);
    Route::get('add-{jenis}', [SupkonproController::class, 'loadAddForm']);
    Route::post('add-{jenis}', [SupkonproController::class, 'AddSupkonpro'])->name('AddSupkonpro');
    Route::get('edit-supkonpro/{id}/{jenis}', [SupkonproController::class, 'loadEditForm']);
    Route::get('delete-supkonpro/{id}/{jenis}', [SupkonproController::class, 'deleteSupkonpro'])->name('supkonpro.delete');
    Route::put('edit-supkonpro', [SupkonproController::class, 'EditSupkonpro'])->name('EditSupkonpro');
});

require __DIR__.'/auth.php';
