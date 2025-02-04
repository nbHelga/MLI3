<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonEmployee extends Model
{
    /** @use HasFactory<\Database\Factories\NonEmployeeFactory> */
    use HasFactory;
    protected $table = 'nonemployees';
    protected $fillable=[
        'status_aktif',
        'nama_ktp',
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
        'no_hp',
        'no_hp_rekan_1',
        'no_hp_rekan_1_ket',
        'no_hp_rekan_2',
        'no_hp_rekan_2_ket',
        'tgl_masuk'
    ];
}
