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
        Schema::table('payout', function (Blueprint $table) {
            $table->integer('bank_fee')->default(0)->after('nominal_approved');
            $table->integer('optimation_fee')->default(0)->after('bank_fee');
            $table->integer('ads_fee')->default(0)->after('optimation_fee');
            $table->integer('platform_fee')->default(0)->after('ads_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payout', function (Blueprint $table) {
            $table->dropColumn('bank_fee');
            $table->dropColumn('optimation_fee');
            $table->dropColumn('ads_fee');
            $table->dropColumn('platform_fee');
        });
    }
};
