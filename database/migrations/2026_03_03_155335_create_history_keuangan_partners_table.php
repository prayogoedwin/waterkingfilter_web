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
        Schema::create('history_keuangan_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->references('id')->on('partners');
            $table->integer('nominal');
            $table->enum('tipe', ['withdrawal', 'topup']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_keuangan_partners');
    }
};
