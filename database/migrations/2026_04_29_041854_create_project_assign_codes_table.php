<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_assign_codes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('code_id');

            $table->string('assign_margin'); // keeping as string per your DB

            $table->timestamps();

            // Indexes
            $table->index('project_id');
            $table->index('code_id');

            // Recommended foreign keys (uncomment if tables exist)
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            // $table->foreign('code_id')->references('id')->on('project_code_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_assign_codes');
    }
};