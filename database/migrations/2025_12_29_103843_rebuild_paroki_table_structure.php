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
        // 1. Clear references in kendaraan table
        DB::table('kendaraan')->update([
            'dipinjam_paroki_id' => null,
            'tarikan_paroki_id' => null,
        ]);

        // 2. Clear references in riwayat_pemakai table
        DB::table('riwayat_pemakai')->update([
            'paroki_id' => null,
        ]);

        // 3. Truncate paroki table
        DB::statement('TRUNCATE TABLE paroki RESTART IDENTITY CASCADE');

        // 4. Drop old columns and add new columns
        Schema::table('paroki', function (Blueprint $table) {
            // Drop old kota column (was string)
            $table->dropColumn('kota');
        });

        Schema::table('paroki', function (Blueprint $table) {
            // Make kevikepan_id nullable (some records have 0)
            $table->foreignId('kevikepan_id')->nullable()->change();

            // Add new columns
            $table->string('kode', 20)->nullable()->after('id');
            $table->string('nama_gereja', 255)->nullable()->after('kevikepan_id');
            // nama already exists
            $table->string('fax', 50)->nullable()->after('telepon');
            $table->decimal('latitude', 18, 14)->nullable()->after('email');
            $table->decimal('longitude', 18, 14)->nullable()->after('latitude');
            $table->unsignedInteger('kota_id')->nullable()->after('alamat');
            $table->unsignedTinyInteger('status_paroki_id')->default(1)->after('longitude');
            $table->unsignedInteger('kecamatan_id')->nullable()->after('status_paroki_id');
            $table->unsignedInteger('kelurahan_id')->nullable()->after('kecamatan_id');
            $table->string('gambar', 255)->nullable()->after('kelurahan_id');
            $table->foreignId('parent_id')->nullable()->after('gambar');

            // Add self-referencing foreign key
            $table->foreign('parent_id')->references('id')->on('paroki')->nullOnDelete();

            // Add indexes
            $table->index('kode');
            $table->index('status_paroki_id');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paroki', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['kode']);
            $table->dropIndex(['status_paroki_id']);
            $table->dropIndex(['parent_id']);

            $table->dropColumn([
                'kode',
                'nama_gereja',
                'fax',
                'latitude',
                'longitude',
                'kota_id',
                'status_paroki_id',
                'kecamatan_id',
                'kelurahan_id',
                'gambar',
                'parent_id',
            ]);

            // Restore kota as string
            $table->string('kota', 100)->nullable()->after('alamat');
        });
    }
};
