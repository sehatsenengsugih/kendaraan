<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paroki', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kevikepan_id')->constrained('kevikepan')->cascadeOnDelete();
            $table->string('nama', 255);
            $table->string('alamat', 500)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('kevikepan_id');
            $table->index('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paroki');
    }
};
