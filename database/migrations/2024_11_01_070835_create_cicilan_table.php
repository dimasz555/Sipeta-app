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
        Schema::create('cicilan', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique()->nullable();

            $table->unsignedBigInteger('pembelian_id');
            $table->foreign('pembelian_id')->references('id')->on('pembelian')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('no_cicilan'); // Nomor bulan cicilan
            $table->integer('harga_cicilan');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->datetime('tgl_bayar')->nullable();
            $table->string('kwitansi')->nullable();
            $table->enum('status', ['belum dibayar', 'lunas', 'batal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicilan');
    }
};
