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
        if (Schema::hasTable('donatur')) {
            if (!Schema::hasColumn('donatur', 'religion')) {
                Schema::table('donatur', function (Blueprint $table) {
                    $table->string('religion')->nullable(true);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('donatur') && Schema::hasColumn('donatur', 'religion')) {
            Schema::table('donatur', function (Blueprint $table) {
                $table->dropColumn('religion');
            });
        }
    }
};
