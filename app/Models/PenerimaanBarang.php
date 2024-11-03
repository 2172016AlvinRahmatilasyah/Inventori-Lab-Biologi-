<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    protected $table = 'master_penerimaan_barangs';

    protected $fillable = [
        'supkonpro_id',
        'user_id',
        'jenis_id',
        'nama_pengantar',
        'keterangan',

    ];

    public function supkonpro()
    {
        return $this->belongsTo(supkonpro::class, 'supkonpro_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jenisPenerimaanBarang()
    {
        return $this->belongsTo(JenisPenerimaan::class, 'jenis_id');
    }
    public function barang()
    {
        return $this->belongsTo(barang::class, 'barang_id');
    }
    public function detailpenerimaanbarang()
    {
        return $this->hasMany(DetailPenerimaanBarang::class, 'master_penerimaan_barang_id');
    }
}
