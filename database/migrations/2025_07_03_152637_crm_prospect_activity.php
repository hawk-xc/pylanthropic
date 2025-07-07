<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_prospect_activity', function (Blueprint $table) {
            $table->id()->autoIncrement()->unique();
            $table->enum('type', ['wa', 'sms', 'email', 'call', 'meeting', 'note', 'task'])->nullable(false);
            $table->text('content')->nullable(false);
            $table->text('description')->nullable(false);
            $table->bigInteger('created_by')->nullable(false)->comment('id_users');
            $table->bigInteger('updated_by')->nullable()->comment('id_users');
            $table->timestamps();

            $table->dateTime('date')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);

            $table->unsignedBigInteger('crm_prospect_id');

            $table->foreign('crm_prospect_id')
                  ->references('id')
                  ->on('crm_prospect')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_prospect_activity');
    }
};
