<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAc extends Model
{
    /** @use HasFactory<\Database\Factories\AssetAcFactory> */
    use HasFactory;
    protected $fillable=[
        'nama', //default AC
        'status', // aktif/nonaktif
        'kode',
        'lokasi',
        'divisi',
        // 'pic', default yg edit 
        'kategori',
        'tipe',
        'kondisi',
    ];

}
