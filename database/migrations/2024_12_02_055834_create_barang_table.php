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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category_id');
            $table->integer('merek_id');
            $table->integer('stok_barang');
            $table->json('mac_address');
            $table->enum('status', ['rusak', 'bagus', 'dalam_perbaikan']);
            $table->enum('lokasi', ['ujung_bawah', 'tengah_bawah', 'ujung_atas', 'tengah_atas']);
            $table->foreign('category_id')->references('id')->onDelete('cascade');
            $table->foreign('merek_id')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
