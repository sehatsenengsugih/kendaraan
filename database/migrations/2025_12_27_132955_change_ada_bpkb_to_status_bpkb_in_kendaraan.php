<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create master table status_bpkb
        Schema::create('status_bpkb', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Insert default status BPKB
        DB::table('status_bpkb')->insert([
            ['id' => 1, 'nama' => 'Ada', 'keterangan' => 'BPKB tersedia dan dimiliki', 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Pinjam Nama', 'keterangan' => 'BPKB atas nama pihak lain', 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Dalam Proses', 'keterangan' => 'BPKB sedang dalam proses pengurusan', 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Diberikan', 'keterangan' => 'BPKB sudah diberikan ke pihak lain', 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Add foreign key column to kendaraan
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->foreignId('status_bpkb_id')->nullable()->after('plat_nomor')->constrained('status_bpkb')->nullOnDelete();
        });

        // 4. Migrate existing data: ada_bpkb = true -> status_bpkb_id = 1 (Ada)
        DB::statement("UPDATE kendaraan SET status_bpkb_id = 1 WHERE ada_bpkb = true");

        // 5. Drop old column
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn('ada_bpkb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back ada_bpkb column
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->boolean('ada_bpkb')->default(false)->after('plat_nomor');
        });

        // Migrate data back: status_bpkb_id = 1 -> ada_bpkb = true
        DB::statement("UPDATE kendaraan SET ada_bpkb = true WHERE status_bpkb_id = 1");

        // Drop foreign key and column
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('status_bpkb_id');
        });

        // Drop master table
        Schema::dropIfExists('status_bpkb');
    }
};
