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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->enum('perihal', ['Lembur', 'Izin', 'Cuti', 'Reimbursement', 'Perjalanan Dinas', 'Survey Pelanggan', 'Penagihan Piutang', 'Lainnya']);
            $table->date('tgl')->nullable();
            $table->decimal('dana', 15, 2)->nullable();
            $table->integer('durasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('dokumen_pelengkap')->nullable();
            $table->enum('status', ['pending', 'terkirim'])->default('pending');
            $table->char('id_employees', 10);
            $table->foreign('id_employees')
              ->references('id')
              ->on('employees')
              ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
