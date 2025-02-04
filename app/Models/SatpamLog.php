<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatpamLog extends Model
{
    protected $fillable = [
        'jam_keluar',
        'nama_tamu',
        'no_hp',
        'keperluan',
        'bertemu_dengan',
        'ada_janji',
    ];
    /** @use HasFactory<\Database\Factories\SatpamLogFactory> */
    use HasFactory;
    
}
