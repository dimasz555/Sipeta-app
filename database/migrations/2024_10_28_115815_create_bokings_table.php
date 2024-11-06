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
        Schema::create('bokings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedBigInteger('blok_id');
            $table->foreign('blok_id')->references('id')->on('bloks')->onDelete('restrict')->onUpdate('cascade');

            $table->string('no_blok');
            $table->datetime('tgl_boking');
            $table->integer('harga_boking');
            $table->datetime('tgl_lunas')->nullable();
            $table->enum('status', ['proses', 'lunas', 'batal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bokings');
    }
};
