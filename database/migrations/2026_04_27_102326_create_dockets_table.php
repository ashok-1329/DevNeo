<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dockets', function (Blueprint $table) {
            $table->id();

            $table->integer('invoice_id')->nullable();
            $table->integer('project_number')->nullable();

            $table->string('docket_number', 100)->nullable();
            $table->date('docket_date');

            $table->foreignId('supplier')->constrained('sub_contractors');
            $table->string('job_code');

            $table->foreignId('sub_contractor')->constrained('sub_contractors');

            $table->string('category', 100)->nullable();

            $table->longText('notes')->nullable();

            $table->boolean('is_invoice')->default(0);

            $table->text('docket_file')->nullable();

            $table->integer('manager_id')->nullable();

            $table->longText('e_signature_file')->nullable();
            $table->longText('approval_e_signature_file')->nullable();

            $table->string('submitted_date')->nullable();

            $table->tinyInteger('status')->default(0)
                ->comment('0=Pending,1=Approved,3=Assigned');

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dockets');
    }
};
