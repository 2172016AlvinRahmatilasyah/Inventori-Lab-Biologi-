<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jenis_barang extends Model
{
    protected $table = 'jenis_barangs';

    protected $fillable = [
        'nama_jenis_barang',
        'satuan_stok'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'jenis_barang_id');  // Adjust the foreign key if needed
    }

}
