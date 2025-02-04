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
        Schema::create('employees', function (Blueprint $table) {
            $table->char('id', 10)->primary();
            $table->string(column:'status_aktif');
            $table->string(column:'nama_ktp', length:50);
            $table->string(column:'nama', length:10);
            $table->string(column:'gender',length:6);
            $table->string(column:'nik',length:16)->unique();
            $table->string('kk',length:16);
            $table->char('sim_jenis_1',length:1)->nullable();
            $table->date('sim_masa_berlaku_1')->nullable();
            $table->char('sim_jenis_2',length:1)->nullable();
            $table->date('sim_masa_berlaku_2')->nullable();
            $table->string('npwp',length:15)->nullable();
            $table->string('status_pajak')->nullable();
            $table->string('kode_perusahaan',10);
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
            $table->string('pendidikan');
            $table->string('nama_sekolah');
            $table->string('email');
            $table->string('no_hp');
            $table->string('no_hp_rekan_1');
            $table->string('no_hp_rekan_1_ket');
            $table->string('no_hp_rekan_2')->nullable();
            $table->string('no_hp_rekan_2_ket')->nullable();
            $table->date('tgl_masuk');
            $table->date('tgl_keluar')->nullable();
            $table->foreign('kode_perusahaan')->references('kode')->on('perusahaans');
            $table->foreign('departemen')->references('kode_dept')->on('departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
