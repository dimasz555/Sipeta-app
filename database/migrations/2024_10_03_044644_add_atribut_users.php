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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');

            $table->string('username')->unique();
            $table->string('phone')->unique();
            $table->enum('gender', ['pria', 'wanita']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique();

            $table->dropColumn('username');
            $table->dropColumn('phone');
            $table->dropColumn('gender');
        });
    }
};
