<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRememberTokenColumnOnMembersTable extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('remember_token', 100)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            // Kembalikan ke timestamp jika dibutuhkan rollback
            $table->timestamp('remember_token')->nullable()->change();
        });
    }
}

