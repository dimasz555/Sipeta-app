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
        Schema::create('pengeluaran_operasional_dan_kas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jenis_pengeluaran_id');
            $table->foreign('jenis_pengeluaran_id')->references('id')->on('jenis_pengeluaran')->onDelete('cascade')->onUpdate('cascade');
            $table->datetime('tgl_pengeluaran');
            $table->string('deskripsi');
            $table->string('kode')->nullable();
            $table->enum('metode_pembayaran', ['tunai', 'transfer']);
            $table->integer('jumlah');
            $table->enum('kategori', ['operasional', 'kas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_operasional_dan_kas');
    }
};
