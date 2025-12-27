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
        DB::statement('DROP TYPE IF EXISTS jenis_servis CASCADE');
        DB::statement('DROP TYPE IF EXISTS status_servis CASCADE');

        // Create enum types
        DB::statement("CREATE TYPE jenis_servis AS ENUM ('rutin', 'perbaikan', 'darurat', 'overhaul')");
        DB::statement("CREATE TYPE status_servis AS ENUM ('dijadwalkan', 'dalam_proses', 'selesai', 'dibatalkan')");

        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->enum('jenis', ['rutin', 'perbaikan', 'darurat', 'overhaul'])->default('rutin');
            $table->date('tanggal_servis');
            $table->date('tanggal_selesai')->nullable();
            $table->integer('kilometer')->nullable(); // Odometer saat servis
            $table->string('bengkel', 255)->nullable(); // Nama bengkel
            $table->text('deskripsi'); // Detail pekerjaan
            $table->text('spare_parts')->nullable(); // Daftar spare parts
            $table->decimal('biaya', 15, 2)->default(0);
            $table->enum('status', ['dijadwalkan', 'dalam_proses', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            $table->string('bukti_path', 255)->nullable(); // Upload nota/bukti
            $table->date('servis_berikutnya')->nullable(); // Jadwal servis berikutnya
            $table->integer('km_berikutnya')->nullable(); // Km untuk servis berikutnya
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamps();
        });

        // Create indexes
        DB::statement('CREATE INDEX idx_servis_kendaraan ON servis(kendaraan_id)');
        DB::statement('CREATE INDEX idx_servis_tanggal ON servis(tanggal_servis)');
        DB::statement('CREATE INDEX idx_servis_status ON servis(status)');
        DB::statement('CREATE INDEX idx_servis_jenis ON servis(jenis)');

        // Index untuk reminder servis terjadwal
        DB::statement("CREATE INDEX idx_servis_reminder ON servis(servis_berikutnya) WHERE status = 'selesai' AND servis_berikutnya IS NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servis');
        DB::statement('DROP TYPE IF EXISTS jenis_servis');
        DB::statement('DROP TYPE IF EXISTS status_servis');
    }
};
