<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalize plat nomor format:
     * - Remove dashes (-)
     * - Add spaces between parts (HURUF ANGKA HURUF)
     * Example: "H-7570-DZ" or "H7570DZ" becomes "H 7570 DZ"
     */
    public function up(): void
    {
        $kendaraan = DB::table('kendaraan')->get();

        foreach ($kendaraan as $k) {
            $plat = $k->plat_nomor;

            // Skip if already in correct format
            if (preg_match('/^[A-Z]{1,2}\s+\d+\s+[A-Z]+$/', $plat)) {
                continue;
            }

            // Remove all spaces and dashes
            $clean = str_replace([' ', '-'], '', $plat);

            // Parse and reformat: (letters)(numbers)(letters)
            if (preg_match('/^([A-Z]{1,2})(\d+)([A-Z]+)$/', $clean, $matches)) {
                $newPlat = $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
                DB::table('kendaraan')
                    ->where('id', $k->id)
                    ->update(['plat_nomor' => $newPlat]);
            }
        }
    }

    /**
     * This migration cannot be reversed as the original format is unknown.
     */
    public function down(): void
    {
        // Cannot reverse - original format unknown
    }
};
