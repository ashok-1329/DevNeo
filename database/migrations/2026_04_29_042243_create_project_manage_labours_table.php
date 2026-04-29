<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_manage_labour', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('labour_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');

            $table->integer('days_in_month')->default(0);
            $table->integer('total_days')->default(0);
            $table->integer('present_days')->default(0);

            $table->json('periods')->nullable();

            $table->timestamps();

            // indexes
            $table->index('labour_id');
            $table->index('project_id');
            $table->index('user_id');

            // optional foreign keys (recommended)
            // $table->foreign('labour_id')->references('id')->on('labour_positions')->onDelete('cascade');
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_manage_labour');
    }
};