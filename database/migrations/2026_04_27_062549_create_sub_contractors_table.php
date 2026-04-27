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
        Schema::create('sub_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('business_name_other')->nullable();

            $table->string('rep_name');

            $table->string('subcontractor_asset_id')->nullable();

            $table->unsignedBigInteger('type_of_work'); // likely FK to services
            $table->string('type_of_work_other')->nullable();

            $table->boolean('is_docket')->default(0);
            $table->tinyInteger('status')->default(0);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_contractors');
    }
};
