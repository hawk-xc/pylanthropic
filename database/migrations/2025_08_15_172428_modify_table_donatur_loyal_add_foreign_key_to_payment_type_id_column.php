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
        Schema::table('donatur_loyal', function (Blueprint $table) {
            $table->foreign('payment_type_id')
                  ->references('id')
                  ->on('payment_type')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donatur_loyal', function (Blueprint $table) {
            $table->dropForeign(['payment_type_id']);
        });
    }
};
