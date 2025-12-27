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
        // Drop enum types if exists (for migrate:fresh)
        DB::statement('DROP TYPE IF EXISTS jenis_pajak CASCADE');
        DB::statement('DROP TYPE IF EXISTS status_pajak CASCADE');

        // Create enum types
        DB::statement("CREATE TYPE jenis_pajak AS ENUM ('tahunan', 'lima_tahunan')");
        DB::statement("CREATE TYPE status_pajak AS ENUM ('belum_bayar', 'lunas', 'terlambat')");

        Schema::create('pajak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->enum('jenis', ['tahunan', 'lima_tahunan'])->default('tahunan');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->decimal('nominal', 15, 2)->nullable(); // Jumlah pajak
            $table->decimal('denda', 15, 2)->default(0); // Denda keterlambatan
            $table->enum('status', ['belum_bayar', 'lunas', 'terlambat'])->default('belum_bayar');
            $table->string('nomor_notice', 100)->nullable(); // Nomor bukti pajak
            $table->string('bukti_path', 255)->nullable(); // Upload bukti bayar
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamps();
        });

        // Create indexes
        DB::statement('CREATE INDEX idx_pajak_kendaraan ON pajak(kendaraan_id)');
        DB::statement('CREATE INDEX idx_pajak_jatuh_tempo ON pajak(tanggal_jatuh_tempo)');
        DB::statement('CREATE INDEX idx_pajak_status ON pajak(status)');

        // Index untuk query reminder (pajak yang akan jatuh tempo dalam 30 hari)
        DB::statement("CREATE INDEX idx_pajak_reminder ON pajak(tanggal_jatuh_tempo) WHERE status = 'belum_bayar'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pajak');
        DB::statement('DROP TYPE IF EXISTS jenis_pajak');
        DB::statement('DROP TYPE IF EXISTS status_pajak');
    }
};
