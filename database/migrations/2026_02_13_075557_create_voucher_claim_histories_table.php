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
        Schema::create('voucher_claim_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->references('id')->on('vouchers');
            $table->foreignId('member_id')->nullable();
            $table->foreignId('partner_id')->nullable();
            $table->foreignId('invoice_id')->nullable();
            $table->string('voucher_name');
            $table->string('voucher_jenis');
            $table->integer('voucher_value');
            $table->integer('dicount_amount');
            $table->integer('subtotal');
            $table->integer('total_before_discount');
            $table->integer('total_after_discount');
            $table->timestamp('claim_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_claim_histories');
    }
};
