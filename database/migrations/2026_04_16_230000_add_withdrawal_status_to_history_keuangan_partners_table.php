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
        Schema::table('history_keuangan_partners', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'proses', 'terbayar'])
                ->default('menunggu')
                ->after('tipe');
            $table->string('keterangan')->nullable()->after('status');
        });

        DB::table('history_keuangan_partners')
            ->where('tipe', 'topup')
            ->update(['status' => 'terbayar']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_keuangan_partners', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};

