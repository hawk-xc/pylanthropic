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
        Schema::create('crm_pipeline', function (Blueprint $table) {
            $table->id()->autoIncrement()->unique();
            $table->string('name')->nullable(false);
            $table->text('description')->nullable(true);
            $table->tinyInteger('percentage_deals')->comment('0-100, 0=lost, 100=deals');
            $table->tinyInteger('sort_number')->nullable(false);
            $table->tinyInteger('is_active')->default(1);
            $table->bigInteger('created_by')->nullable(false)->comment('id_users');
            $table->bigInteger('updated_by')->nullable()->comment('id_users');
            $table->timestamps();

            $table->unsignedBigInteger('crm_leads_id');

            $table->foreign('crm_leads_id')
                  ->references('id')
                  ->on('crm_leads')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_pipeline');
    }
};
