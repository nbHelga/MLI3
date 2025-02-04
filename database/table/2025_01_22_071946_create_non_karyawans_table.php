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
        Schema::create('non_karyawans', function (Blueprint $table) {
            // $table->id();
            $table->string(column:'nik',length:16)->primary();
            $table->boolean(column:'status_aktif');
            $table->string(column:'nama_ktp', length:50);
            $table->string(column:'nama_panggil', length:10);
            $table->string(column:'gender',length:5);
            $table->string('kk',length:16);
            $table->string('kode_perusahaan');
            $table->string('nama_perusahaan');
            $table->string('departemen');
            $table->string('jabatan');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->integer('usia');
            $table->text('alamat_ktp');
            $table->text('alamat_skrg');
            $table->string('agama');
            $table->string('status_nikah');
            $table->string('kewarganegaraan');
            $table->char('sim_jenis',length:1);
            $table->date('sim_masa_berlaku');
            $table->string('no_hp');
            $table->string('no_hp_rekan_1');
            $table->string('no_hp_rekan_1_ket');
            $table->string('no_hp_rekan_2');
            $table->string('no_hp_rekan_2_ket');
            $table->date('tgl_masuk');
            $table->date('tgl_keluar');
            $table->foreign('departemen')->references('kode_dept')->on('departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_karyawans');
    }
};
