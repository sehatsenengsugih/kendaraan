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
        Schema::create('kevikepan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->string('kode', 20)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Create index
        DB::statement('CREATE INDEX idx_kevikepan_kode ON kevikepan(kode)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kevikepan');
    }
};
