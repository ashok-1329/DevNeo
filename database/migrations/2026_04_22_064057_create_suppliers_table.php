<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_category');
            $table->integer('payment_term')->nullable();
            $table->string('supplier_name');
            $table->string('supplier_email');
            $table->string('supplier_phone');
            $table->string('supplier_address');
            $table->string('supplier_abn');
            $table->string('supplier_bank_name');
            $table->string('supplier_bsb_no');
            $table->string('supplier_account_number');
            $table->string('supplier_branch')->nullable();
            $table->string('supplier_account_name');
            $table->string('supplier_bank_email');
            $table->string('payment_terms');
            $table->string('supplier_notes')->nullable();
            $table->string('supplier_representative')->nullable();
            $table->enum('supplier_rank', ['1', '2', '3'])->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('supplier_category')->references('id')->on('supplier_categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
