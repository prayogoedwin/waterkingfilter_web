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
        Schema::create('whatsapp_broadcast_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_id')->index();
            $table->string('judul_pesan');
            $table->text('isi_pesan');
            $table->string('target_type');
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('no_whatsapp');
            $table->string('status')->default('failed')->index();
            $table->text('error_message')->nullable();
            $table->longText('response_body')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_broadcast_histories');
    }
};

