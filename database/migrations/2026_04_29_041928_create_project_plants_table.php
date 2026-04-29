<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_plants', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');

            $table->string('plant_id'); // kept string as per your DB
            $table->unsignedBigInteger('plant_type')->nullable();
            $table->unsignedBigInteger('plant_capacity')->nullable();

            $table->string('registration_number')->nullable();
            $table->date('registration_expiry_date')->nullable();

            $table->string('make_of_asset')->nullable();
            $table->string('model_of_asset')->nullable();

            $table->string('plant_name');
            $table->string('plant_code')->nullable();

            $table->string('supplier'); // string in your DB
            $table->string('unit');

            $table->string('rate'); // keeping as string (not ideal)

            $table->smallInteger('is_docket')->default(0)->comment('0=No for docket,1=Yes for docket');
            $table->smallInteger('add_to_diary')->default(0)->comment('1= Add to Diary,0=No add to diary');

            $table->timestamps();

            // Index
            $table->index('project_id');

            // Optional FK
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_plants');
    }
};