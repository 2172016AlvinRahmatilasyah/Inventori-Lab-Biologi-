<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    protected $table = 'master_pengeluaran_barangs';

    protected $fillable = [
        'supkonpro_id',
        'user_id',
        'jenis_id',
        'nama_pengambil',
        'keterangan',
        'harga_invoice',
    ];

    public function supkonpro()
    {
        return $this->belongsTo(supkonpro::class, 'supkonpro_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jenisPengeluaranBarang()
    {
        return $this->belongsTo(JenisPengeluaran::class, 'jenis_id');
    }
    public function barang()
    {
        return $this->belongsTo(barang::class, 'barang_id');
    }
    public function detailpengeluaranbarang()
    {
        return $this->hasMany(DetailPengeluaranBarang::class, 'master_pengeluaran_barang_id');
    }
}
