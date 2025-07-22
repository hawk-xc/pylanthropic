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
        Schema::table('grab_organization', function (Blueprint $table) {
            $table->boolean('is_affiliated')->default(0)->after('is_interest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grab_organization', function (Blueprint $table) {
            $table->dropColumn('is_affiliated');
        });
    }
};
