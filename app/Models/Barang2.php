<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
class Barang2 extends Model
{
    use HasFactory;

    protected $table = 'barang2';

    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode', 'nama', 'kualitas', 'size'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = Carbon::now('Asia/Jakarta');
            $model->updated_at = Carbon::now('Asia/Jakarta');
        });
    }
}
