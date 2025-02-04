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
        'nama_ktp',
        'status_aktif',
        'nama',
        'gender',
        'nik',
        'kk',
        'sim_jenis_1',
        'sim_masa_berlaku_1',
        'sim_jenis_2',
        'sim_masa_berlaku_2',
        'kode_perusahaan',
        'departemen',
        'jabatan',
        'tempat_lahir',
        'tgl_lahir',
        'usia',
        'alamat_ktp',
        'alamat_skrg',
        'agama',
        'status_nikah',
        'kewarganegaraan',
        'pendidikan',
        'nama_sekolah',
        'email',
        'email_company',
        'no_hp',
        'no_hp_rekan_1',
        'no_hp_rekan_1_ket',
        'no_hp_rekan_2',
        'no_hp_rekan_2_ket',
        'tgl_masuk'
    ];

    public function users()
    {
        return $this->hasOne(User::class, 'id');
    }

    public function suhu()
    {
        return $this->hasMany(Suhu::class, 'id_employees', 'id');
    }

    public function surats()
    {
        return $this->hasMany(Surat::class, 'id_employees', 'id');
    }
} 