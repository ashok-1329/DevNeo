<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('asset_type');
            $table->unsignedBigInteger('asset_capacity')->nullable();

            $table->string('asset_number');
            $table->string('asset_name')->nullable();
            $table->string('asset_code');

            $table->unsignedBigInteger('supplier')->nullable()->comment('supplier cat id');

            $table->string('registration_number');
            $table->date('registration_expiry_date');

            $table->string('make_of_asset')->nullable();
            $table->string('model_of_asset')->nullable();

            $table->unsignedBigInteger('unit')->nullable();

            $table->string('rate')->nullable(); // kept as string as per your DB
            $table->string('year_of_manufacture')->nullable();

            $table->longText('asset_description')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->tinyInteger('status')->default(1);

            $table->timestamps();

            // Optional indexes (recommended)
            $table->index('asset_type');
            $table->index('supplier');
            $table->index('unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};