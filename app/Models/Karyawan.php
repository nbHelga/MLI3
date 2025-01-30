<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Karyawan extends Model
{
    protected $fillable = [
        'id_employees',
        'nama_ktp',
        'nama_panggil',
        'gender',
        'nik',
        'kk',
        'kode_perusahaan',
        'nama_perusahaan',
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
        'tgl_masuk',
        ];
    
    /** @use HasFactory<\Database\Factories\KaryawanFactory> */
    use HasFactory;
}
