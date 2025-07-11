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
        Schema::table('donatur_short_links', function (Blueprint $table) {
            $table->integer('click_counter')->default(0);
            $table->date('last_accessed_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donatur_short_links', function (Blueprint $table) {
            $table->dropColumn('click_counter');
            $table->dropColumn('last_accessed_at');
        });
    }
};
