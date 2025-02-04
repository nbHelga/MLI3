<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';
    
    protected $fillable = [
        'id_employees',
        'perihal',
        'tgl',
        'dana',
        'durasi',
        'keterangan',
        'dokumen_pelengkap',
        'status'
    ];

    protected $casts = [
        'tgl' => 'date',
        'dana' => 'decimal:2',
        'durasi' => 'integer'
    ];

    // Relasi ke model Employees
    public function employees()
    {
        return $this->belongsTo(Employees::class, 'id_employees', 'id');
    }
} 