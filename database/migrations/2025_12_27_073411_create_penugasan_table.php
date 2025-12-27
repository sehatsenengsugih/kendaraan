<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop enum type if exists (for migrate:fresh)
        DB::statement('DROP TYPE IF EXISTS status_penugasan CASCADE');

        // Create enum type for status penugasan
        DB::statement("CREATE TYPE status_penugasan AS ENUM ('aktif', 'selesai', 'dibatalkan')");

        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->foreignId('pemegang_id')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('pengguna')->onDelete('restrict'); // Admin yang assign
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable(); // Null = belum selesai
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->text('tujuan')->nullable(); // Tujuan penugasan
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Create indexes
        DB::statement('CREATE INDEX idx_penugasan_kendaraan ON penugasan(kendaraan_id)');
        DB::statement('CREATE INDEX idx_penugasan_pemegang ON penugasan(pemegang_id)');
        DB::statement('CREATE INDEX idx_penugasan_status ON penugasan(status)');
        DB::statement('CREATE INDEX idx_penugasan_tanggal ON penugasan(tanggal_mulai, tanggal_selesai)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan');
        DB::statement('DROP TYPE IF EXISTS status_penugasan');
    }
};
