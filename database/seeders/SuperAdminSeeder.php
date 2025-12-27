<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::updateOrCreate(
            ['email' => 'admin@kas.or.id'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@kas.or.id',
                'phone' => '024-8316180',
                'role' => Pengguna::ROLE_SUPER_ADMIN,
                'user_type' => null, // Super admin tidak perlu user_type
                'organization_name' => null,
                'status' => Pengguna::STATUS_ACTIVE,
                'password' => Hash::make('password123'), // Ganti di production!
            ]
        );

        // Buat juga sample admin dan user untuk testing
        Pengguna::updateOrCreate(
            ['email' => 'pic@kas.or.id'],
            [
                'name' => 'PIC Kendaraan',
                'email' => 'pic@kas.or.id',
                'phone' => '024-8316181',
                'role' => Pengguna::ROLE_ADMIN,
                'user_type' => null,
                'organization_name' => null,
                'status' => Pengguna::STATUS_ACTIVE,
                'password' => Hash::make('password123'),
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'user@kas.or.id'],
            [
                'name' => 'Pengguna Demo',
                'email' => 'user@kas.or.id',
                'phone' => '081234567890',
                'role' => Pengguna::ROLE_USER,
                'user_type' => Pengguna::TYPE_PRIBADI,
                'organization_name' => null,
                'status' => Pengguna::STATUS_ACTIVE,
                'password' => Hash::make('password123'),
            ]
        );
    }
}
