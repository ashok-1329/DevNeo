<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_pricing_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('code_id')->nullable();
            $table->string('item');
            $table->string('description')->nullable();
            $table->decimal('qty', 10, 2)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('code')->nullable();
            $table->timestamps();

            // optional indexes
            $table->index('project_id');
            $table->index('code_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_pricing_schedules');
    }
};