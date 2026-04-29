<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plant_capacities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('plant_type_id')
                  ->comment('Plant Type id');

            $table->string('name');
            $table->tinyInteger('status')->default(1);

            $table->timestamps();

            // Index
            $table->index('plant_type_id');

            // Recommended FK
            // $table->foreign('plant_type_id')
            //       ->references('id')
            //       ->on('plant_types')
            //       ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_capacities');
    }
};