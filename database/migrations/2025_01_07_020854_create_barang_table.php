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
            $table->increments('id_barang');
            $table->string('nama_barang');
            $table->string('merek', 100);
            $table->integer('stok_barang')->nullable();
            $table->string('kode_barang')->nullable()->unique();
            $table->string('qrcode_image')->nullable();
            $table->unsignedInteger('id_jenis_barang');
            $table->foreign('id_jenis_barang')
                  ->references('id_jenis_barang')
                  ->on('jenis_barang')
                  ->onDelete('cascade');
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
