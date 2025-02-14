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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_periode_pemeliharaan');
            $table->text('deskripsi_tugas');
            $table->enum('status', ['sedang diproses', 'dalam perbaikan', 'selesai']);
            $table->date('tanggal_pelaksanaan');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_periode_pemeliharaan')->references('id')->on('periode_pemeliharaans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
