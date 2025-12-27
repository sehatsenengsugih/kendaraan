<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make garasi_id nullable
        DB::statement('ALTER TABLE kendaraan ALTER COLUMN garasi_id DROP NOT NULL');

        // Make tanggal_perolehan nullable
        DB::statement('ALTER TABLE kendaraan ALTER COLUMN tanggal_perolehan DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reverting to NOT NULL may fail if there are null values
        DB::statement('ALTER TABLE kendaraan ALTER COLUMN garasi_id SET NOT NULL');
        DB::statement('ALTER TABLE kendaraan ALTER COLUMN tanggal_perolehan SET NOT NULL');
    }
};
