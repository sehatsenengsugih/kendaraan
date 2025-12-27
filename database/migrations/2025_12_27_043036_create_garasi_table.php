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
        Schema::create('garasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('kode_pos', 10)->nullable();
            $table->foreignId('kevikepan_id')->constrained('kevikepan')->onDelete('restrict');
            $table->string('pic_name', 255)->nullable(); // Person in charge
            $table->string('pic_phone', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create indexes
        DB::statement('CREATE INDEX idx_garasi_kevikepan ON garasi(kevikepan_id) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_garasi_kota ON garasi(kota) WHERE deleted_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garasi');
    }
};
