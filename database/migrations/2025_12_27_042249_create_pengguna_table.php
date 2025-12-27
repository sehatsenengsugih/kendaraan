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
        // Drop enum types if exist (for migrate:fresh)
        DB::statement('DROP TYPE IF EXISTS role_type CASCADE');
        DB::statement('DROP TYPE IF EXISTS user_type CASCADE');
        DB::statement('DROP TYPE IF EXISTS user_status CASCADE');

        // Create enum types for PostgreSQL
        DB::statement("CREATE TYPE role_type AS ENUM ('super_admin', 'admin', 'user')");
        DB::statement("CREATE TYPE user_type AS ENUM ('pribadi', 'paroki', 'lembaga')");
        DB::statement("CREATE TYPE user_status AS ENUM ('active', 'inactive')");

        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('phone', 20)->nullable();

            // Role: super_admin, admin, user
            $table->enum('role', ['super_admin', 'admin', 'user'])->default('user');

            // User Type: pribadi, paroki, lembaga (nullable for non-user role)
            $table->enum('user_type', ['pribadi', 'paroki', 'lembaga'])->nullable();

            // Organization name (required if user_type is paroki or lembaga)
            $table->string('organization_name', 255)->nullable();

            // Status: active, inactive
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Password (Argon2id hash)
            $table->string('password', 255);

            // Google SSO ID
            $table->string('google_id', 255)->nullable();

            // Laravel remember token
            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();
        });

        // Create indexes
        DB::statement('CREATE INDEX idx_pengguna_email ON pengguna(email) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_pengguna_role ON pengguna(role) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_pengguna_status ON pengguna(status) WHERE deleted_at IS NULL');

        // Add constraints
        // user_type wajib diisi jika role = 'user'
        DB::statement("
            ALTER TABLE pengguna ADD CONSTRAINT chk_user_type
            CHECK (role != 'user' OR user_type IS NOT NULL)
        ");

        // organization_name wajib jika user_type bukan pribadi
        DB::statement("
            ALTER TABLE pengguna ADD CONSTRAINT chk_organization_name
            CHECK (user_type = 'pribadi' OR user_type IS NULL OR organization_name IS NOT NULL)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');

        // Drop enum types
        DB::statement('DROP TYPE IF EXISTS role_type');
        DB::statement('DROP TYPE IF EXISTS user_type');
        DB::statement('DROP TYPE IF EXISTS user_status');
    }
};
