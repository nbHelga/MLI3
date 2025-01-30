<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apar extends Model
{
    /** @use HasFactory<\Database\Factories\AparFactory> */
    use HasFactory;
    protected $fillable=[
        'kode',
        'lokasi',
        'nama',
        'ukuran',
        'tgl_exp',
        'tgl_produksi',
        'keterangan',
        'keterangan_refill',
    ];
}
