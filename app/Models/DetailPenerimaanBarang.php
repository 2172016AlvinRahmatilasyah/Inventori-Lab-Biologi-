<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenerimaanBarang extends Model
{
    protected $table = 'detail_penerimaan_barangs';

    protected $fillable = [
        'master_penerimaan_barang_id',
        'barang_id',
        'jumlah_diterima',
        'harga',
        'total_harga',

    ];

    public function penerimaanBarang()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'master_penerimaan_barang_id');
    }
    public function barang()
    {
        return $this->belongsTo(barang::class, 'barang_id');
    }
    public function supkonpro()
    {
        return $this->belongsTo(supkonpro::class, 'supkonpro_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function jenispenerimaanbarang()
    {
        return $this->belongsTo(JenisPenerimaan::class, 'jenis_id');
    }
}
