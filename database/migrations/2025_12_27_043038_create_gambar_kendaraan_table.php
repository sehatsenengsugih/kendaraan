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
        Schema::create('gambar_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->string('file_path', 255);
            $table->string('file_name', 255);
            $table->string('mime_type', 50);
            $table->unsignedInteger('file_size'); // in bytes
            $table->string('caption', 255)->nullable();
            $table->unsignedSmallInteger('urutan')->default(0); // untuk ordering gambar
            $table->timestamps();
        });

        // Create index for kendaraan lookup
        DB::statement('CREATE INDEX idx_gambar_kendaraan_id ON gambar_kendaraan(kendaraan_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_kendaraan');
    }
};
