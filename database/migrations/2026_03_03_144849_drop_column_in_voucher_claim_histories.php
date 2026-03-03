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
        Schema::table('voucher_claim_histories', function (Blueprint $table) {
            $table->dropColumn('voucher_name');
            $table->dropColumn('voucher_jenis');
            $table->dropColumn('voucher_value');
            $table->dropColumn('dicount_amount');
            $table->dropColumn('subtotal');
            $table->dropColumn('total_before_discount');
            $table->dropColumn('total_after_discount');
            $table->dropColumn('claim_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_claim_histories', function (Blueprint $table) {
            //
        });
    }
};
