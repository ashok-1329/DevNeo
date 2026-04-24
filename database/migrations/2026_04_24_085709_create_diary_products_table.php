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
        Schema::create('diary_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Correct column
            $table->unsignedBigInteger('category_id');

            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ✅ Correct foreign key
            $table->foreign('category_id')
                ->references('id')
                ->on('diary_product_categories')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary_products');
    }
};
