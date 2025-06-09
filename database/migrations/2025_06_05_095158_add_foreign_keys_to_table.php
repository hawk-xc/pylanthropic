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
        // donatur loyal schema
        Schema::table('donatur_loyal', function (Blueprint $table) {
            if (!Schema::hasColumn('donatur_loyal', 'donatur_id')) {
                $table->unsignedBigInteger('donatur_id');
            }

            $table->foreign('donatur_id')->references('id')->on('donatur')->onDelete('cascade');
        });

        // program schema
        Schema::table('donatur_loyal', function (Blueprint $table) {
            if (!Schema::hasColumn('donatur_loyal', 'program_id')) {
                $table->unsignedBigInteger('program_id');
            }

            $table->foreign('program_id')->references('id')->on('program')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donatur_loyal', function (Blueprint $table) {
            $table->dropForeign(['donatur_id']);
        });

        Schema::table('donatur_loyal', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
        });
    }
};
