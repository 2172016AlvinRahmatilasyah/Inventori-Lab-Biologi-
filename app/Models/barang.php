<?php

namespace App\Models;

// use App\Models\barang;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    protected $table = 'barangs';

    protected $fillable = [
        'nama_barang',
        'stok',
        'kadaluarsa',
        'lokasi'
    ];

    public function jenisBarang()
    {
        return $this->belongsTo(jenis_barang::class, 'jenis_barang_id'); // Pastikan nama kelas ditulis dengan huruf besar
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
        return $this->hasMany(DetailPenerimaanBarang::class, 'master_penerimaan_barang_id');
    }
    public function detailpengeluaranbarang()
    {
        return $this->hasMany(DetailPengeluaranBarang::class, 'master_pengeluaran_barang_id');
    }

    public function jenisPenerimaanBarang()
    {
        return $this->belongsTo(JenisPenerimaan::class, 'jenis_id');
    }
    public function jenisPengeluaranBarang()
    {
        return $this->belongsTo(JenisPengeluaran::class, 'jenis_id');
    }

    public function supkonpro()
    {
        return $this->belongsTo(supkonpro::class, 'supkonpro_id');
    }
}
