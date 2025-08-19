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
        Schema::create('crm_prospect', function (Blueprint $table) {
            $table->id()->autoIncrement()->unique();
            $table->string('name')->nullable(false);
            
            // relation foreign key column
            $table->unsignedBigInteger('crm_pipeline_id');
            $table->unsignedBigInteger('donatur_id')->nullable(true);
            
            $table->text('description');
            $table->integer('nominal')->nullable(true)->default(0);
            $table->bigInteger('assign_to')->default(0);
            $table->tinyInteger('is_potential')->default(0);
            $table->bigInteger('created_by')->nullable(false)->comment('id_users');
            $table->bigInteger('updated_by')->nullable()->comment('id_users');
            $table->timestamps();


            $table->foreign('donatur_id')
                  ->references('id')
                  ->on('donatur')
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
        Schema::dropIfExists('crm_prospect');
    }
};
