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
        Schema::create('detail_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_peminjaman');
            $table->date('tgl_kembali');
            $table->enum('status', ['proses_pengajuan', 'sudah_dikembalikan', 'dipinjam', 'pengajuan_ditolak'])->nullable();
            $table->string('ket_ditolak_pengajuan', 100)->nullable();
            $table->enum('kondisi_barang_akhir', ['lengkap', 'tidak_lengkap', 'rusak'])->nullable();
            $table->string('ket_tidak_lengkap_awal', 100)->nullable();
            $table->string('ket_tidak_lengkap_akhir', 100)->nullable();
            $table->foreign('id_peminjaman')->references('id')->on('peminjamans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamans');
    }
};
