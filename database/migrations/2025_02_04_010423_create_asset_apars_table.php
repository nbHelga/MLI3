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
        Schema::create('asset_apars', function (Blueprint $table) {
            $table->string('kode',length:3)->primary();
            $table->string('lokasi');
            $table->string('nama',length:10);
            $table->string('ukuran');
            $table->date('tgl_exp')->nullable();
            $table->date('tgl_produksi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('keterangan_refill')->nullable();
            $table->bigInteger('biaya_refill')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_apars');
    }
};
