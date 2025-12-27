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
        // Drop existing constraint
        DB::statement('ALTER TABLE pengguna DROP CONSTRAINT IF EXISTS pengguna_role_check');

        // Add new constraint with admin_servis role
        DB::statement("ALTER TABLE pengguna ADD CONSTRAINT pengguna_role_check CHECK (role IN ('super_admin', 'admin', 'user', 'admin_servis'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new constraint
        DB::statement('ALTER TABLE pengguna DROP CONSTRAINT IF EXISTS pengguna_role_check');

        // Restore original constraint (without admin_servis)
        DB::statement("ALTER TABLE pengguna ADD CONSTRAINT pengguna_role_check CHECK (role IN ('super_admin', 'admin', 'user'))");
    }
};
