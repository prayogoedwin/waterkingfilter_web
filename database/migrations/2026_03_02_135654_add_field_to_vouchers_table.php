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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->date('fixed_date')->nullable()->after('waktu_id');
            $table->date('period_start')->nullable()->after('fixed_date');
            $table->date('period_end')->nullable()->after('period_start');
            $table->json('specific_dates')->nullable()->after('period_end');
            $table->json('specific_days')->nullable()->after('specific_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            //
        });
    }
};
