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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('boking_id')->nullable();
            $table->foreign('boking_id')->references('id')->on('bokings')->onDelete('set null')->onUpdate('cascade');

            $table->datetime('tgl_pembelian');
            $table->integer('harga');
            $table->integer('dp');
            $table->integer('jumlah_bulan_cicilan');
            $table->integer('harga_cicilan_perbulan');
            $table->string('pjb')->nullable();
            $table->datetime('tgl_lunas')->nullable();
            $table->enum('status', ['proses', 'selesai', 'batal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
