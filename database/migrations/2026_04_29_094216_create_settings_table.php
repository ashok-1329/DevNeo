<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('name');        // e.g. project, margin
            $table->unsignedInteger('type'); // 1=project, 2=margin etc

            $table->string('key');         // config key
            $table->longText('value');     // config value

            $table->timestamps();

            // optional index for faster lookup
            $table->index(['name', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};