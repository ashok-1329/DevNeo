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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code_id')->nullable();
            $table->string('project_name')->nullable();
            $table->tinyInteger('project_region')->comment('1=metro,2=regional');
            $table->string('project_other_region')->nullable();
            $table->string('project_number');
            $table->longText('project_description')->nullable();
            $table->longText('project_address')->nullable();
            $table->text('project_notes')->nullable();

            // Client info
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('client_representative')->nullable();
            $table->string('client_rep_email')->nullable();
            $table->string('superintendent_rep')->nullable();
            $table->string('superintendent_rep_email')->nullable();
            $table->string('client_phone_number', 100)->nullable();
            $table->text('client_address')->nullable();
            $table->string('invoices_sent_to')->nullable();

            // Team
            $table->unsignedBigInteger('project_manager')->nullable();
            $table->unsignedBigInteger('project_engineer')->nullable();
            $table->bigInteger('contract_admin')->nullable();
            $table->integer('construction_manager')->nullable();
            $table->unsignedBigInteger('supervisor')->nullable();

            // Contract
            $table->tinyInteger('contractor_type')->nullable()->comment('1=Principal,2=Subcontractor');
            $table->string('superintendent')->nullable();
            $table->unsignedBigInteger('contract_type')->nullable();
            $table->string('contract_type_other')->nullable();
            $table->string('contract_number')->nullable();
            $table->date('commencement_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('payment_term')->nullable();
            $table->string('payment_term_other')->nullable();
            $table->string('claims_certification_period')->nullable();
            $table->string('claims_certification_period_other')->nullable();
            $table->tinyInteger('lump_sum')->nullable()->default(0)->comment('0=disable,1=enable');
            $table->string('defect_liability_period')->nullable();
            $table->tinyInteger('schedule_of_rate')->nullable()->default(0)->comment('0=disable,1=enable');

            // Financial
            $table->string('contract_value')->nullable();
            $table->string('contract_value_gst')->nullable();
            $table->string('profit_value')->nullable();
            $table->string('provisional_sum_total')->nullable();
            $table->string('provisional_sum_total_gst')->nullable();
            $table->string('assign_profit_margin')->nullable();
            $table->string('assign_profit_margin_value')->nullable();
            $table->string('insurance_percentage')->nullable();
            $table->string('insurance_percentage_value')->nullable();
            $table->smallInteger('bank_guarantee_required')->nullable();

            // Retention — Bank Guarantee
            $table->string('practical_completion')->nullable();
            $table->string('custom_practical_completion')->nullable();
            $table->string('practical_completion_amount')->nullable();
            $table->string('final_completion')->nullable();
            $table->string('custom_final_completion')->nullable();
            $table->string('final_completion_amount')->nullable();

            // Cash Retentions
            $table->smallInteger('cash_retentions_required')->nullable();
            $table->string('cash_practical_completion')->nullable();
            $table->string('custom_cash_practical_completion')->nullable();
            $table->string('cash_practical_completion_amount')->nullable();
            $table->string('cash_final_completion')->nullable();
            $table->string('custom_cash_final_completion')->nullable();
            $table->string('cash_final_completion_amount')->nullable();

            // Flags
            $table->smallInteger('is_project_pricing_schedule')->default(0)->comment('0=Sheet not Import,1=Sheet import');
            $table->smallInteger('is_project_material')->default(0)->comment('0=no record,1=record found');
            $table->smallInteger('is_project_plant')->default(0)->comment('0=no record,1=record found');
            $table->smallInteger('is_project_labourer')->default(0)->comment('0=no record,1=record found');

            $table->tinyInteger('status')->default(0)->comment('1=active,2=deactive,3=archive,4=defects period,5=complete');
            $table->integer('step')->default(0)->comment('Project setup 13 step');
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
