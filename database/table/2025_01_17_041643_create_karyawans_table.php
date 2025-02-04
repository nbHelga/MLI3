<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string(column:'id_employees', length:15);//[kode_perusahaan-kode_departemen-nomor_urut]
            $table->string(column:'nama_ktp', length:50);
            $table->string(column:'nama', length:10);
            $table->string(column:'gender',length:5);
            $table->string(column:'nik',length:16)->unique();
            $table->string('kk');
            $table->string('kode_perusahaan');
            $table->string('nama_perusahaan');
            $table->string('departemen', 3);
            $table->string('jabatan');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->integer('usia');
            $table->text('alamat_ktp');
            $table->text('alamat_skrg');
            $table->string('agama');
            $table->string('status_nikah');
            $table->string('kewarganegaraan');
            $table->string('pendidikan');
            $table->string('nama_sekolah');
            $table->string('email');
            $table->string('email_company');
            $table->string('no_hp');
            $table->string('no_hp_rekan_1');
            $table->string('no_hp_rekan_1_ket');
            $table->string('no_hp_rekan_2');
            $table->string('no_hp_rekan_2_ket');
            $table->date('tgl_masuk');
            $table->date('tgl_keluar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
