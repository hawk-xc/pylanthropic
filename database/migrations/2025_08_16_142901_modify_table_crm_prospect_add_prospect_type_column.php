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
            $table->unsignedBigInteger('donatur_id')->nullable(true)->after('id')->change();
            
            if (!Schema::hasColumn('crm_prospect', 'prospect_type')) {
                $table->enum('prospect_type', ['donatur', 'organization', 'grab_organization'])->default('donatur')->after('nominal');
            }

            if (!Schema::hasColumn('crm_prospect', 'organization_id')) {
                $table->bigInteger('organization_id')->nullable(true)->after('donatur_id');

                $table->foreign('organization_id')
                  ->references('id')
                  ->on('organization')
                  ->onDelete('cascade');
            }

            if (!Schema::hasColumn('crm_prospect', 'grab_organization_id')) {
                $table->bigInteger('grab_organization_id')->nullable(true)->after('organization_id');

                $table->foreign('grab_organization_id')
                ->references('id')
                ->on('grab_organization')
                ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('crm_prospect', function (Blueprint $table) {
            if (Schema::hasColumn('crm_prospect', 'organization_id')) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            }

            if (Schema::hasColumn('crm_prospect', 'grab_organization_id')) {
                $table->dropForeign(['grab_organization_id']);
                $table->dropColumn('grab_organization_id');
            }

            if (Schema::hasColumn('crm_prospect', 'prospect_type')) {
                $table->dropColumn('prospect_type');
            }

            $table->unsignedBigInteger('donatur_id')->nullable(false)->change();
        });
    }
};
