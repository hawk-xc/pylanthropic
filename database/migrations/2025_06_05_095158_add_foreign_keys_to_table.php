<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah donatur.id menjadi unsigned
        DB::statement('ALTER TABLE donatur MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        
        // 2. Ubah program.id menjadi unsigned (jika belum)
        DB::statement('ALTER TABLE program MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        
        // 3. Tambahkan foreign keys setelah memastikan semua tipe cocok
        Schema::table('donatur_loyal', function (Blueprint $table) {
            // Pastikan kolom referensi juga unsigned
            $table->unsignedBigInteger('donatur_id')->change();
            $table->unsignedBigInteger('program_id')->change();
            
            // Tambahkan foreign keys
            $table->foreign('donatur_id')
                  ->references('id')
                  ->on('donatur')
                  ->onDelete('cascade');
            
            $table->foreign('program_id')
                  ->references('id')
                  ->on('program')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donatur_loyal', function (Blueprint $table) {
            $table->dropForeign(['donatur_id']);
            $table->dropForeign(['program_id']);
        });
        
        // Kembalikan ke signed (opsional)
        DB::statement('ALTER TABLE donatur MODIFY id BIGINT AUTO_INCREMENT');
        DB::statement('ALTER TABLE program MODIFY id BIGINT AUTO_INCREMENT');
    }
};
