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
        Schema::create('partner_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->references('id')->on('partners');
            $table->foreignId('voucher_id')->references('id')->on('vouchers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_vouchers');
    }
};
