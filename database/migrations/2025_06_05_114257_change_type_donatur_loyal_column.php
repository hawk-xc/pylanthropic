<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::table('donatur_loyal', function (Blueprint $table) {
            // Rename column jika ada
            if (Schema::hasColumn('donatur_loyal', 'every_moment')) {
                $table->renameColumn('every_moment', 'every_day');
            }
        });

        // Kolom 'every_time' diubah tipe-nya
        if (Schema::hasColumn('donatur_loyal', 'every_time')) {
            DB::statement("ALTER TABLE donatur_loyal MODIFY every_time TIME NULL");
        }

        // Tambah kolom jika belum ada
        Schema::table('donatur_loyal', function (Blueprint $table) {
            if (!Schema::hasColumn('donatur_loyal', 'every_date')) {
                $table->date('every_date')->nullable()->after('every_time');
            }
            if (!Schema::hasColumn('donatur_loyal', 'every_date_period')) {
                $table->integer('every_date_period')->nullable()->after('every_time');
            }
            if (!Schema::hasColumn('donatur_loyal', 'every_month_period')) {
                $table->integer('every_month_period')->nullable()->after('every_date_period');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donatur_loyal', function (Blueprint $table) {
            if (Schema::hasColumn('donatur_loyal', 'every_day')) {
                $table->renameColumn('every_day', 'every_moment');
            }

            if (Schema::hasColumn('donatur_loyal', 'every_time')) {
                DB::statement("ALTER TABLE donatur_loyal MODIFY every_time DATETIME NULL");
            }

            if (Schema::hasColumn('donatur_loyal', 'every_date')) {
                $table->dropColumn('every_date');
            }
            if (Schema::hasColumn('donatur_loyal', 'every_date_period')) {
                $table->dropColumn('every_date_period');
            }
            if (Schema::hasColumn('donatur_loyal', 'every_month_period')) {
                $table->dropColumn('every_month_period');
            }
        });
    }
};
