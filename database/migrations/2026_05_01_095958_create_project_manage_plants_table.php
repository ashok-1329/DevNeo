<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_manage_plants', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('record_id')->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('plant_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->json('periods');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_manage_plants');
    }
};