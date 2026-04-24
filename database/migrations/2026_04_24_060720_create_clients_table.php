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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            $table->string('client_name');
            $table->string('client_abn');
            $table->string('client_phone');
            $table->string('client_representative');
            $table->string('client_rep_email');

            $table->string('client_account_email')->nullable();
            $table->string('client_terms')->nullable();

            $table->longText('client_address');
            $table->longText('internal_note')->nullable();

            $table->string('client_logo');

            $table->tinyInteger('status')->default(1)
                ->comment('0=Deactive,1=active,2=block');

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
        Schema::dropIfExists('clients');
    }
};
