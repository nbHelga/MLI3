<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode',
        'nama',
        'size',
        'kualitas',
    ];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $now = Carbon::now('Asia/Jakarta');
            $model->created_at = $now;
            $model->updated_at = $now;
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now('Asia/Jakarta');
        });
    }
} 