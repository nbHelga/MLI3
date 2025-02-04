<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $fillable = ['kode', 'nama'];
    
    /** @use HasFactory<\Database\Factories\PerusahaanFactory> */
    use HasFactory;

}
