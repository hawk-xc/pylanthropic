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
        Schema::table('banners', function (Blueprint $table) {
            $table->date('expire_date')->nullable()->after('created_by')->description('banner/popup expiration date');
            $table->boolean('is_forever')->default(0)->nullable()->after('expire_date');
            $table->dropColumn('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->integer('duration');
            $table->dropColumn('expire_date');
            $table->dropColumn('is_forever');
        });
    }
};
