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
        DB::statement('DROP TYPE IF EXISTS status_kendaraan CASCADE');

        // Create enum type for status kendaraan
        DB::statement("CREATE TYPE status_kendaraan AS ENUM ('aktif', 'nonaktif', 'dihibahkan')");

        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('plat_nomor', 20)->unique();
            $table->string('nomor_bpkb', 50)->unique();
            $table->foreignId('merk_id')->constrained('merk')->onDelete('restrict');
            $table->string('nama_model', 100);
            $table->integer('tahun_pembuatan');
            $table->string('warna', 50);
            $table->enum('jenis', ['mobil', 'motor']); // jenis kendaraan
            $table->foreignId('garasi_id')->constrained('garasi')->onDelete('restrict');
            $table->foreignId('pemegang_id')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->enum('status', ['aktif', 'nonaktif', 'dihibahkan'])->default('aktif');
            $table->date('tanggal_perolehan');
            $table->date('tanggal_hibah')->nullable();
            $table->text('catatan')->nullable();
            $table->string('avatar_path', 255)->nullable(); // Gambar utama kendaraan
            $table->timestamps();
            $table->softDeletes();
        });

        // Create indexes for common queries
        DB::statement('CREATE INDEX idx_kendaraan_merk ON kendaraan(merk_id) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_kendaraan_garasi ON kendaraan(garasi_id) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_kendaraan_pemegang ON kendaraan(pemegang_id) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_kendaraan_status ON kendaraan(status) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_kendaraan_jenis ON kendaraan(jenis) WHERE deleted_at IS NULL');

        // Composite index for filtering
        DB::statement('CREATE INDEX idx_kendaraan_jenis_status ON kendaraan(jenis, status) WHERE deleted_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
        DB::statement('DROP TYPE IF EXISTS status_kendaraan');
    }
};
