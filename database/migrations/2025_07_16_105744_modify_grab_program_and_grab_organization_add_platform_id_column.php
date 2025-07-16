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
        Schema::table('grab_program', function (Blueprint $table) {
            $table->unsignedBigInteger('platform_id')->nullable(true)->after('user_id');
        });

        Schema::table('grab_organization', function (Blueprint $table) {
            $table->unsignedBigInteger('platform_id')->nullable(true)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grab_program', function (Blueprint $table) {
            $table->dropColumn('platform_id');
        });
        Schema::table('grab_organization', function (Blueprint $table) {
            $table->dropColumn('platform_id');
        });
    }
};
