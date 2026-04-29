<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('claim_progress_schedule_lists', function (Blueprint $table) {
            $table->id();

            $table->integer('claim_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('code_id')->nullable();

            $table->integer('qty')->nullable();

            $table->date('claim_date')->nullable();

            $table->timestamps();

            // indexes (recommended)
            $table->index('claim_id');
            $table->index('project_id');
            $table->index('code_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_progress_schedule_lists');
    }
};