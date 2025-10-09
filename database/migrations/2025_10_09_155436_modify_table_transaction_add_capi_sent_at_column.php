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
        Schema::table('transaction', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction', 'capi_sent_at')) {
                $table->dateTime('capi_sent_at')->nullable(true)->after('fbc');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction', function (Blueprint $table) {
            if (Schema::hasColumn('transaction', 'capi_sent_at')) {
                $table->dropColumn('capi_sent_at');
            }
        });
    }
};
