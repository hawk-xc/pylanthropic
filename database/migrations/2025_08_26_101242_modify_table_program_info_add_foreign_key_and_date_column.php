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
        Schema::table('program_info', function (Blueprint $table) {
            if (!Schema::hasColumn('program_info', 'program_id')) {
                $table->unsignedBigInteger('program_id');
                $table->foreign('program_id')->references('id')->on('program');
            }
            if (!Schema::hasColumn('program_info', 'date')) {
                $table->date('date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_info', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn('date');
        });
    }
};
