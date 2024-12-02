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
}
