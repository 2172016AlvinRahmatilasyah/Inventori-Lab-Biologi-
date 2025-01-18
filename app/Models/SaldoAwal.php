<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
    protected $table = 'saldo_awals';

    protected $fillable = [
        'barang_id',
        'tahun',
        'bulan',
        'saldo_awal',
        'total_terima',
        'total_keluar',
        'saldo_akhir',

    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function penerimaanBarang()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'master_penerimaan_barang_id');
    }
    public function pengeluaranBarang()
    {
        return $this->belongsTo(PengeluaranBarang::class, 'master_pengeluaran_barang_id');
    }
    public function detailpenerimaanbarang()
    {
        return $this->hasMany(DetailPenerimaanBarang::class, 'barang_id');
    }
    public function detailpengeluaranbarang()
    {
        return $this->hasMany(DetailPengeluaranBarang::class, 'barang_id');
    }
    public function supkonpro()
    {
        return $this->belongsTo(supkonpro::class, 'supkonpro_id');
    }
}
