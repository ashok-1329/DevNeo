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
        Schema::create('project_labours', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->unsignedBigInteger('user_id');

            $table->string('name');

            $table->unsignedBigInteger('employment_type');
            $table->tinyInteger('employer_type')->nullable();
            $table->integer('position');
            $table->string('rate');

            $table->unsignedBigInteger('employer_supplier');

            $table->smallInteger('add_to_diary')->default(0);
            $table->boolean('assign_to_project')->default(0);

            $table->tinyInteger('labour_type')->nullable(); // 1=neo,2=hire

            $table->integer('region_id')->nullable();

            $table->string('labour_agency')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_labours');
    }
};
