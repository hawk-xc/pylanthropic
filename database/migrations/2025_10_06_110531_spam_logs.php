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
        if (!Schema::hasTable('spam_logs')) {
            Schema::create('spam_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('transaction_id')->nullable();
                $table->string('device_id', 64)->nullable();
                $table->string('session_id')->nullable();
                $table->string('ua_core', 100)->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('reason', 255)->nullable();
                $table->string('fingerprint_id')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spam_logs');
    }
};
