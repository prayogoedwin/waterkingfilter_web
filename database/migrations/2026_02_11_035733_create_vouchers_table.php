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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('voucher_tipe_id')->references('id')->on('voucher_tipes');
            $table->foreignId('voucher_jenis_id')->references('id')->on('voucher_jenis');
            $table->foreignId('voucher_penggunaan_id')->references('id')->on('voucher_penggunaans');
            $table->foreignId('voucher_partner_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
