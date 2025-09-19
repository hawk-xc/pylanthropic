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
        Schema::create('token_config_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('token_config_id');
            $table->text('description')->nullable(false);
            $table->string('token')->nullable(false);
            $table->integer('created_by')->nullable(false);
            $table->timestamps();

            $table->foreign('token_config_id')->references('id')->on('token_configs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_config_logs');
    }
};
