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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_aset');//forklift/ac
            $table->date('tanggal');
            $table->string('lokasi');
            $table->string('merek_aset');
            $table->boolean('is_perbaikan');//khusus AC
            $table->boolean('is_pemeliharaan'); //khusus AC
            $table->bigInteger('biaya');
            $table->text('keterangan');
            $table->string('pic');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
