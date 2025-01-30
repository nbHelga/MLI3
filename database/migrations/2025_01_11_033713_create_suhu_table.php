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
        Schema::create('suhu', function (Blueprint $table) {
            $table->id();
            $table->integer('suhu');
            $table->time('jam');
            $table->string('keterangan', 20);
            $table->string('gambar')->nullable();
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
        Schema::dropIfExists('suhu');
    }
};
