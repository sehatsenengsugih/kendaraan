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
        // Tambah kolom dokumen di tabel kendaraan
        Schema::table('kendaraan', function (Blueprint $table) {
            // Untuk status kepemilikan "Milik Paroki"
            $table->foreignId('pemilik_paroki_id')->nullable()->after('pemilik_lembaga_id')
                ->constrained('paroki')->nullOnDelete();

            // Dokumen BPKB dan STNK (PDF)
            $table->string('dokumen_bpkb_path')->nullable()->after('nomor_bpkb');
            $table->string('dokumen_stnk_path')->nullable()->after('dokumen_bpkb_path');
        });

        // Tambah kolom dokumen serah terima di tabel riwayat_pemakai
        Schema::table('riwayat_pemakai', function (Blueprint $table) {
            $table->string('dokumen_serah_terima_path')->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropForeign(['pemilik_paroki_id']);
            $table->dropColumn(['pemilik_paroki_id', 'dokumen_bpkb_path', 'dokumen_stnk_path']);
        });

        Schema::table('riwayat_pemakai', function (Blueprint $table) {
            $table->dropColumn('dokumen_serah_terima_path');
        });
    }
};
