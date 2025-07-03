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
        Schema::create('leads_crm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donatur_id');
            $table->unsignedBigInteger('program_id');
            $table->string('lead_stage')->default('contacted');
            $table->integer('lead_stack')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('donatur_id')->references('id')->on('donatur')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('program')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_crm');
    }
};
