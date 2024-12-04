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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('id_kategori');
            $table->foreign('id_kategori')->references('id')->on('kategoris')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('stok_barang')->nullable();
            $table->json('mac_address');
            $table->string('token_qr')->nullable();
            $table->enum('status', ['Rusak', 'Bagus', 'Dalam perbaikan']);
            $table->unsignedBigInteger('id_lokasi');
            $table->foreign('id_lokasi')->references('id')->on('lokasis')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
