<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->enum('settlement_method', ['postpaid', 'prepaid'])
                ->default('postpaid')
                ->after('remember_token');
        });

        DB::statement("
            ALTER TABLE history_keuangan_partners
            MODIFY COLUMN tipe ENUM('withdrawal', 'topup', 'claim_debit') NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE history_keuangan_partners
            MODIFY COLUMN tipe ENUM('withdrawal', 'topup') NOT NULL
        ");

        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('settlement_method');
        });
    }
};

