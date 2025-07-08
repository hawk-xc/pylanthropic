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
        Schema::create('crm_prospect_logs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('created_by')->nullable(false);
            $table->string('pipeline_name')->nullable(true);
            $table->timestamps();

            $table->unsignedBigInteger('crm_prospect_id');
            $table->unsignedBigInteger('crm_pipeline_id');

            $table->foreign('crm_prospect_id')
                  ->references('id')
                  ->on('crm_prospect')
                  ->onDelete('cascade');

            $table->foreign('crm_pipeline_id')
                  ->references('id')
                  ->on('crm_pipeline')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_prospect_logs');
    }
};
