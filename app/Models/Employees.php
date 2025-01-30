<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $primaryKey = 'id';

    public $incrementing = false; // Karena id adalah char(10)

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama',
        'divisi',
    ];

    public function users()
    {
        return $this->hasOne(User::class, 'id');
    }

    public function suhu()
    {
        return $this->hasMany(Suhu::class, 'id_employees', 'id');
    }
} 