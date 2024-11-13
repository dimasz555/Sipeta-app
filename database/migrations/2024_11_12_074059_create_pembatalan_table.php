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
        Schema::create('pembatalan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pembelian_id');
            $table->foreign('pembelian_id')->references('id')->on('pembelian')->onDelete('cascade')->onUpdate('cascade');

            $table->string('alasan_pembatalan');
            $table->datetime('tgl_pembatalan');
            $table->integer('jumlah_pengembalian');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembatalan');
    }
};
