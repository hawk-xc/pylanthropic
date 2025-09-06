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
            if (!Schema::hasColumn('payout', 'file_submit')) {
                $table->string('file_submit')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('payout', 'file_paid')) {
                $table->string('file_paid')->nullable()->after('file_submit');
            }
            if (!Schema::hasColumn('payout', 'file_accepted')) {
                $table->string('file_accepted')->nullable()->after('file_paid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payout', function (Blueprint $table) {
            $table->dropColumn('file_submit');
            $table->dropColumn('file_paid');
            $table->dropColumn('file_accepted');
        });
    }
};
