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
        Schema::create('payment_rules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('supplier_name');

            $table->date('payment_date');

            $table->unsignedBigInteger('frequency_payment_id');

            $table->string('payment_terms')->nullable();
            $table->date('end_date');
            $table->date('last_deducted_at')->nullable();
            $table->string('value_inc_gst');

            $table->string('project_number');
            $table->string('project_code')->nullable();

            $table->longText('payment_description')->nullable();

            $table->string('document_path')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active,0=inactive');
            $table->timestamps();

            $table->foreign('supplier_name')
                ->references('id')
                ->on('suppliers')
                ->restrictOnDelete();

            $table->foreign('frequency_payment_id')
                ->references('id')
                ->on('frequency_payments')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_rules');
    }
};
