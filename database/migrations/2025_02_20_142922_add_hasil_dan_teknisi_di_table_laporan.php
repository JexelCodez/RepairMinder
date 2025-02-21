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
        Schema::table('laporans', function (Blueprint $table) {
            $table->text('hasil_laporan')->nullable()->after('tanggal_laporan');
            $table->unsignedBigInteger('id_teknisi')->nullable()->after('id_user');
            $table->foreign('id_teknisi')->references('id')->on('teknisis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn('hasil_laporan');
            $table->dropColumn('id_teknisi');
        });
    }
};
