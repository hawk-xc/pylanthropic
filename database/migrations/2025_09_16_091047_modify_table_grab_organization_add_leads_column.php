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
            $table->boolean('add_leads')->default(false)->after('is_affiliated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grab_organization', function (Blueprint $table) {
            $table->dropColumn('add_leads');
        });
    }
};
