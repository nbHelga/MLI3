<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Suhu extends Model
{
    use HasFactory;

    protected $table = 'suhu';

    protected $fillable = [
        'id_tempat',
        'suhu',
        'jam',
        'keterangan',
        'gambar',
        'id_employees'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'id_employees', 'id');
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat');
    }

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
