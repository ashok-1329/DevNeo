<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('payment_term_id')
                ->nullable()
                ->after('supplier_account_name')
                ->constrained('payment_terms')
                ->nullOnDelete();

            $table->unsignedSmallInteger('payment_term_days')
                ->nullable()
                ->after('payment_term_id');
        });

        // Migrate existing string values to the new FK — runs after seeder data is present
        if (Schema::hasColumn('suppliers', 'payment_terms')) {
            DB::statement('
                UPDATE suppliers s
                JOIN payment_terms pt ON pt.name = s.payment_terms
                SET s.payment_term_id   = pt.id,
                    s.payment_term_days = pt.days
            ');

            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('payment_terms');
            });
        }
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('payment_terms', 50)->nullable()->after('supplier_account_name');
        });

        // Restore string values from FK before dropping columns
        DB::statement('
            UPDATE suppliers s
            JOIN payment_terms pt ON pt.id = s.payment_term_id
            SET s.payment_terms = pt.name
        ');

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['payment_term_id']);
            $table->dropColumn(['payment_term_id', 'payment_term_days']);
        });
    }
};
