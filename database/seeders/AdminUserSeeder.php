<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $storeA = Store::where('name', 'Toko A')->first();
        $storeB = Store::where('name', 'Toko B')->first();

        // Super Admin (no store — can access all)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@tokobangunan.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'store_id' => null,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Store Admin for Toko A
        $storeAdminA = User::firstOrCreate(
            ['email' => 'admin.tokoa@tokobangunan.com'],
            [
                'name' => 'Admin Toko A',
                'password' => bcrypt('password'),
                'store_id' => $storeA?->id,
                'email_verified_at' => now(),
            ]
        );
        $storeAdminA->assignRole('store_admin');

        // Store Admin for Toko B
        $storeAdminB = User::firstOrCreate(
            ['email' => 'admin.tokob@tokobangunan.com'],
            [
                'name' => 'Admin Toko B',
                'password' => bcrypt('password'),
                'store_id' => $storeB?->id,
                'email_verified_at' => now(),
            ]
        );
        $storeAdminB->assignRole('store_admin');

        // Cashier for Toko A
        $cashierA = User::firstOrCreate(
            ['email' => 'kasir.tokoa@tokobangunan.com'],
            [
                'name' => 'Kasir Toko A',
                'password' => bcrypt('password'),
                'store_id' => $storeA?->id,
                'email_verified_at' => now(),
            ]
        );
        $cashierA->assignRole('cashier');

        // Central Sales
        $centralSales = User::firstOrCreate(
            ['email' => 'sales@tokobangunan.com'],
            [
                'name' => 'Sales Pusat',
                'password' => bcrypt('password'),
                'store_id' => null,
                'email_verified_at' => now(),
            ]
        );
        $centralSales->assignRole('central_sales');
    }
}
