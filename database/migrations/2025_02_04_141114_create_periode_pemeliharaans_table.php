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
        Schema::create('periode_pemeliharaans', function (Blueprint $table) {
            $table->id();
            $table->integer('periode');
            $table->string('kode_barang');
            $table->string('kode_barang_kecil')->nullable();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_maintenance_selanjutnya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_pemeliharaans', function (Blueprint $table) {
            $table->dropColumn('tanggal_maintenance_selanjutnya');
        });
    }
};
