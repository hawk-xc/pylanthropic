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
        Schema::create('short_link', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable(false);
            $table->string('code', 10)->nullable(false);
            $table->text('direct_link')->nullable(false);
            $table->text('description')->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable(false)->comment('id_users');
            $table->bigInteger('updated_by')->nullable()->comment('id_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_link');
    }
};
