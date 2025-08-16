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
        Schema::table('crm_prospect', function (Blueprint $table) {
            $table->enum('prospect_type', ['donatur', 'organization', 'grab_organization'])->default('donatur')->after('nominal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_prospect', function (Blueprint $table) {
            $table->dropColumn('prospect_type');
        });
    }
};
