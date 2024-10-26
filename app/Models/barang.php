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
}
