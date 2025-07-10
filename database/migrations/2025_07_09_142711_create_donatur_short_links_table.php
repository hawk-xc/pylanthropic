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
        Schema::create('donatur_short_links', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('code')->nullable(false);
            $table->boolean('is_active')->default(1)->nullable(false);
            $table->string('amount')->nullable(false);
            $table->string('payment_type')->nullable(false);
            $table->text('description')->nullable(true);
            $table->integer('created_by')->nullable(false);
            $table->integer('updated_by')->nullable(true);
            $table->string('direct_link')->nullable(true);
            $table->timestamps();

            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('donatur_id');

            $table->foreign('program_id')->references('id')->on('program');
            $table->foreign('donatur_id')->references('id')->on('donatur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donatur_short_links');
    }
};
