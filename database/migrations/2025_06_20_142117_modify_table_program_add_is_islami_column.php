<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('program', 'is_islami')) {
            Schema::table('program', function (Blueprint $table) {
                $table->tinyInteger('is_islami')->default(1)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('program', 'is_islami')) {
            Schema::table('program', function (Blueprint $table) {
                $table->dropColumn('is_islami');
            });
        }
    }
};
