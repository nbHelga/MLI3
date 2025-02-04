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
        Schema::create('apars', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('lokasi');
            $table->string('nama');
            $table->string('ukuran');
            $table->date('tgl_exp');
            $table->date('tgl_produksi');
            $table->string('keterangan');
            $table->string('keterangan_refill');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apars');
    }
};
