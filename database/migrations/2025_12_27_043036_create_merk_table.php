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
        DB::statement('DROP TYPE IF EXISTS jenis_kendaraan CASCADE');

        // Create enum type for jenis kendaraan
        DB::statement("CREATE TYPE jenis_kendaraan AS ENUM ('mobil', 'motor')");

        Schema::create('merk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->enum('jenis', ['mobil', 'motor']); // Mobil atau Motor
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint: nama + jenis
            $table->unique(['nama', 'jenis']);
        });

        // Create index
        DB::statement('CREATE INDEX idx_merk_jenis ON merk(jenis) WHERE deleted_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merk');
        DB::statement('DROP TYPE IF EXISTS jenis_kendaraan');
    }
};
