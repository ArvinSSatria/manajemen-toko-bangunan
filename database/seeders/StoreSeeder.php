<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        Store::firstOrCreate(
            ['name' => 'Toko A'],
            [
                'address' => 'Jl. Raya Utama No. 1, Jakarta',
                'phone' => '021-1234567',
                'is_active' => true,
            ]
        );

        Store::firstOrCreate(
            ['name' => 'Toko B'],
            [
                'address' => 'Jl. Merdeka No. 45, Bandung',
                'phone' => '022-7654321',
                'is_active' => true,
            ]
        );
    }
}
