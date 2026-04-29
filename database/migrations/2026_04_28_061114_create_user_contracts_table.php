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
        Schema::create('user_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // indexed
            $table->string('employment_name');
            $table->string('salary_rate');
            $table->string('payment_made'); // 1=Weekly, 2=Fortnightly
            $table->string('timesheet'); // 1=Daily, 2=Weekly
            $table->tinyInteger('staff'); // 1=Office Staff, 2=Field Staff

            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_extension')->nullable();

            $table->longText('notes')->nullable();

            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_contracts');
    }
};
