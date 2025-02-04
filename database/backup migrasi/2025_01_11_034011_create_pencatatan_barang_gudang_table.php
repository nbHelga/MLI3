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
        Schema::create('pencatatan_barang_gudang', function (Blueprint $table) {
            $table->id();
            $table->char('kode_pallet', 5)->notNull(); 
            $table->string('id_barang', 30);
            $table->foreign('id_barang')->references('kode')->on('barang');
            $table->char('id_employees', 10);
            $table->foreign('id_employees')->references('id')->on('employees');
            $table->foreignId('id_tempat')->constrained('tempat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencatatan_barang_gudang');
    }
};
