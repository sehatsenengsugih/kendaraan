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
        Schema::table('riwayat_pemakai', function (Blueprint $table) {
            // Tambah kolom pengguna_id untuk link ke user aplikasi
            $table->foreignId('pengguna_id')->nullable()->after('lembaga_id')
                ->constrained('pengguna')->nullOnDelete();

            // Index untuk query cepat
            $table->index('pengguna_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pemakai', function (Blueprint $table) {
            $table->dropForeign(['pengguna_id']);
            $table->dropIndex(['pengguna_id']);
            $table->dropColumn('pengguna_id');
        });
    }
};
