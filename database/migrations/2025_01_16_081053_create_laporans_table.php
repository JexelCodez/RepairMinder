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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('nama_barang')->nullable();
            $table->string('merk_barang')->nullable();
            $table->string('kode_barang')->nullable();
            $table->text('deskripsi_laporan')->nullable();
            $table->string('bukti_laporan')->nullable();
            $table->string('lokasi_barang')->nullable();
            $table->enum('status', ['pending', 'processed', 'done'])->default('pending');
            $table->date('tanggal_laporan'); 
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
