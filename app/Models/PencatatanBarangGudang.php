<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PencatatanBarangGudang extends Model
{
    use HasFactory;

    protected $table = 'pencatatan_barang_gudang';

    protected $fillable = [
        'kode_pallet',
        'id_barang',
        'id_employees',
        'id_tempat',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'kode');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'id_employees', 'id');
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat', 'id');
    }

    protected static function boot()
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