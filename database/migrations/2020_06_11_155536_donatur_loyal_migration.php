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
         if (!Schema::hasTable('donatur_loyal')) {
            Schema::create('donatur_loyal', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('donatur_id');
                $table->unsignedBigInteger('program_id');
                $table->integer('nominal');
                $table->integer('payment_type_id')->nullable();
                $table->text('desc')->nullable();
                $table->enum('every_period', ['daily', 'weekly', 'monthly', 'yearly', 'other']);
                $table->time('every_time')->nullable();
                $table->integer('every_date_period')->nullable();
                $table->integer('every_month_period')->nullable();
                $table->date('every_date')->nullable();
                $table->string('every_day', 150)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('created_at')->useCurrent();
                $table->unsignedBigInteger('created_by');
                $table->dateTime('updated_at')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('donatur_loyal')) {
            Schema::dropIfExists('donatur_loyal');
        }
    }
};
