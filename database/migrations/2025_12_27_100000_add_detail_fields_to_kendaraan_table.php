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
        Schema::table('kendaraan', function (Blueprint $table) {
            // Dokumen & Identitas
            $table->boolean('ada_bpkb')->default(false)->after('nomor_bpkb');
            $table->string('nomor_rangka', 50)->nullable()->after('ada_bpkb');
            $table->string('nomor_mesin', 50)->nullable()->after('nomor_rangka');

            // Kepemilikan
            $table->string('status_kepemilikan', 20)->default('milik_kas')->after('status');
            $table->string('nama_pemilik_lembaga', 255)->nullable()->after('status_kepemilikan');

            // Pembelian
            $table->date('tanggal_beli')->nullable()->after('tanggal_perolehan');
            $table->decimal('harga_beli', 15, 2)->nullable()->after('tanggal_beli');

            // Penjualan
            $table->date('tanggal_jual')->nullable()->after('tanggal_hibah');
            $table->decimal('harga_jual', 15, 2)->nullable()->after('tanggal_jual');
            $table->string('nama_pembeli', 255)->nullable()->after('harga_jual');

            // Hibah
            $table->string('nama_penerima_hibah', 255)->nullable()->after('nama_pembeli');

            // Pinjam
            $table->boolean('is_dipinjam')->default(false)->after('nama_penerima_hibah');
            $table->string('dipinjam_oleh', 255)->nullable()->after('is_dipinjam');
            $table->date('tanggal_pinjam')->nullable()->after('dipinjam_oleh');

            // Tarikan
            $table->boolean('is_tarikan')->default(false)->after('tanggal_pinjam');
            $table->string('tarikan_dari', 255)->nullable()->after('is_tarikan');
            $table->string('tarikan_pemakai', 255)->nullable()->after('tarikan_dari');
            $table->text('tarikan_kondisi')->nullable()->after('tarikan_pemakai');
        });

        // Make nomor_bpkb nullable
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->string('nomor_bpkb', 50)->nullable()->change();
        });

        // Add 'dijual' to status enum for PostgreSQL
        DB::statement("ALTER TABLE kendaraan DROP CONSTRAINT IF EXISTS kendaraan_status_check");
        DB::statement("ALTER TABLE kendaraan ADD CONSTRAINT kendaraan_status_check CHECK (status IN ('aktif', 'nonaktif', 'dihibahkan', 'dijual'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore status constraint
        DB::statement("ALTER TABLE kendaraan DROP CONSTRAINT IF EXISTS kendaraan_status_check");
        DB::statement("ALTER TABLE kendaraan ADD CONSTRAINT kendaraan_status_check CHECK (status IN ('aktif', 'nonaktif', 'dihibahkan'))");

        Schema::table('kendaraan', function (Blueprint $table) {
            // Drop all new columns
            $table->dropColumn([
                'ada_bpkb',
                'nomor_rangka',
                'nomor_mesin',
                'status_kepemilikan',
                'nama_pemilik_lembaga',
                'tanggal_beli',
                'harga_beli',
                'tanggal_jual',
                'harga_jual',
                'nama_pembeli',
                'nama_penerima_hibah',
                'is_dipinjam',
                'dipinjam_oleh',
                'tanggal_pinjam',
                'is_tarikan',
                'tarikan_dari',
                'tarikan_pemakai',
                'tarikan_kondisi',
            ]);
        });

        // Make nomor_bpkb required again
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->string('nomor_bpkb', 50)->nullable(false)->change();
        });
    }
};
