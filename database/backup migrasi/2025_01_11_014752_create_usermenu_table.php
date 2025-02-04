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
        Schema::create('usermenu', function (Blueprint $table) {
            $table->char('id_users', 10);
            $table->foreignId('id_menu')->constrained(table: 'menu');
            $table->primary(['id_users', 'id_menu']); // Menjadikan kombinasi sebagai primary key
            $table->foreign('id_users')->references('id')->on('users'); // Menambahkan foreign key constraint
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usermenu');
    }
}; 