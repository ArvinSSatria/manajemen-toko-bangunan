<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions grouped by module
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Stores
            'stores.view', 'stores.create', 'stores.edit', 'stores.delete',

            // Users
            'users.view', 'users.create', 'users.edit', 'users.delete',

            // Categories
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',

            // Products
            'products.view', 'products.create', 'products.edit', 'products.delete',

            // Inventory
            'inventory.view', 'inventory.edit',

            // Customers
            'customers.view', 'customers.create', 'customers.edit', 'customers.delete',

            // POS / Sales
            'sales.view', 'sales.create', 'sales.void',

            // Receivables
            'receivables.view', 'receivables.pay',

            // Income
            'income.view', 'income.create', 'income.edit', 'income.delete',

            // Expenses
            'expenses.view', 'expenses.create', 'expenses.edit', 'expenses.delete',

            // BO (Supplier)
            'bo.view', 'bo.create', 'bo.edit', 'bo.delete',

            // Stock Transfer
            'transfers.view', 'transfers.create', 'transfers.approve',

            // Reports
            'reports.view', 'reports.export',

            // Cashflow
            'cashflow.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin — gets all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Store Admin — everything except store management & user management
        $storeAdmin = Role::firstOrCreate(['name' => 'store_admin']);
        $storeAdmin->givePermissionTo([
            'dashboard.view',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'inventory.view', 'inventory.edit',
            'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
            'sales.view', 'sales.create', 'sales.void',
            'receivables.view', 'receivables.pay',
            'income.view', 'income.create', 'income.edit', 'income.delete',
            'expenses.view', 'expenses.create', 'expenses.edit', 'expenses.delete',
            'bo.view', 'bo.create', 'bo.edit', 'bo.delete',
            'transfers.view', 'transfers.create', 'transfers.approve',
            'reports.view', 'reports.export',
            'cashflow.view',
        ]);

        // Cashier — sales, customers, basic views
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->givePermissionTo([
            'dashboard.view',
            'products.view',
            'inventory.view',
            'customers.view', 'customers.create', 'customers.edit',
            'sales.view', 'sales.create',
            'receivables.view', 'receivables.pay',
        ]);

        // Central Sales — view only
        $centralSales = Role::firstOrCreate(['name' => 'central_sales']);
        $centralSales->givePermissionTo([
            'dashboard.view',
            'products.view',
            'inventory.view',
        ]);
    }
}
