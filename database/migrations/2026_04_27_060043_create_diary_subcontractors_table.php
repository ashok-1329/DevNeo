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
        Schema::create('diary_subcontractors', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('subcontractor_id');
            $table->integer('assets_id'); // ⚠️ better as integer if it's an ID
            $table->integer('work_type');

            $table->string('unit');
            $table->string('docket')->nullable();
            $table->string('docket_file')->nullable();

            $table->unsignedBigInteger('submitted_by');

            $table->date('diary_date');
            $table->string('diary_id');

            $table->integer('project_id')->nullable();

            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->tinyInteger('status');
            $table->boolean('is_publish')->default(1); // 0 = draft, 1 = publish

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary_subcontractors');
    }
};
