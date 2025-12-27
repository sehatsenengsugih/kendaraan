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
        Schema::table('kendaraan', function (Blueprint $table) {
            // For dipinjam_oleh - now references paroki
            $table->foreignId('dipinjam_paroki_id')->nullable()->after('dipinjam_oleh')->constrained('paroki')->nullOnDelete();

            // For nama_pemilik_lembaga - now references lembaga
            $table->foreignId('pemilik_lembaga_id')->nullable()->after('nama_pemilik_lembaga')->constrained('lembaga')->nullOnDelete();

            // For tarikan_dari - could be a paroki or lembaga
            $table->foreignId('tarikan_paroki_id')->nullable()->after('tarikan_dari')->constrained('paroki')->nullOnDelete();
            $table->foreignId('tarikan_lembaga_id')->nullable()->after('tarikan_paroki_id')->constrained('lembaga')->nullOnDelete();
        });

        // Also add paroki/lembaga reference to riwayat_pemakai
        Schema::table('riwayat_pemakai', function (Blueprint $table) {
            $table->foreignId('paroki_id')->nullable()->after('nama_pemakai')->constrained('paroki')->nullOnDelete();
            $table->foreignId('lembaga_id')->nullable()->after('paroki_id')->constrained('lembaga')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pemakai', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lembaga_id');
            $table->dropConstrainedForeignId('paroki_id');
        });

        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tarikan_lembaga_id');
            $table->dropConstrainedForeignId('tarikan_paroki_id');
            $table->dropConstrainedForeignId('pemilik_lembaga_id');
            $table->dropConstrainedForeignId('dipinjam_paroki_id');
        });
    }
};
