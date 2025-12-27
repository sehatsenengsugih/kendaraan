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
        Schema::create('riwayat_pemakai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->string('nama_pemakai', 255);
            $table->string('jenis_pemakai', 20)->default('lembaga'); // 'lembaga' or 'pribadi'
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable(); // null = masih aktif
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index('kendaraan_id');
            $table->index(['kendaraan_id', 'tanggal_mulai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pemakai');
    }
};
