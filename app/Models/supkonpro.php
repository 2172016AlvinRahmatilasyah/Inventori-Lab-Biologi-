<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class supkonpro extends Model
{
    protected $table = 'supkonpros';

    protected $fillable = [
        'nama',
        'alamat',
        'kota',
        'telepon',
        'jenis',
        'status',
    ];
}
