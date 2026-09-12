<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            'Semen' => [
                ['name' => 'Semen Tiga Roda 50kg', 'unit' => 'sak', 'purchase' => 55000, 'sell' => 65000],
                ['name' => 'Semen Holcim 50kg', 'unit' => 'sak', 'purchase' => 52000, 'sell' => 62000],
                ['name' => 'Semen Padang 40kg', 'unit' => 'sak', 'purchase' => 45000, 'sell' => 55000],
            ],
            'Cat' => [
                ['name' => 'Cat Dulux Weathershield 5L', 'unit' => 'kaleng', 'purchase' => 280000, 'sell' => 340000],
                ['name' => 'Cat Nippon Paint Vinilex 5L', 'unit' => 'kaleng', 'purchase' => 150000, 'sell' => 195000],
                ['name' => 'Cat Avian 1L', 'unit' => 'kaleng', 'purchase' => 35000, 'sell' => 48000],
            ],
            'Besi' => [
                ['name' => 'Besi Beton 10mm', 'unit' => 'batang', 'purchase' => 65000, 'sell' => 80000],
                ['name' => 'Besi Beton 8mm', 'unit' => 'batang', 'purchase' => 42000, 'sell' => 55000],
                ['name' => 'Besi Hollow 40x40', 'unit' => 'batang', 'purchase' => 85000, 'sell' => 105000],
            ],
            'Paku & Mur' => [
                ['name' => 'Paku 2 inch', 'unit' => 'kg', 'purchase' => 18000, 'sell' => 25000],
                ['name' => 'Paku 3 inch', 'unit' => 'kg', 'purchase' => 18000, 'sell' => 25000],
                ['name' => 'Mur Baut 10mm', 'unit' => 'pcs', 'purchase' => 1500, 'sell' => 3000],
            ],
            'Bata & Batako' => [
                ['name' => 'Bata Merah Press', 'unit' => 'pcs', 'purchase' => 800, 'sell' => 1200],
                ['name' => 'Batako Press', 'unit' => 'pcs', 'purchase' => 3500, 'sell' => 5000],
                ['name' => 'Bata Ringan Hebel', 'unit' => 'pcs', 'purchase' => 8000, 'sell' => 11000],
            ],
            'Pipa & Sanitasi' => [
                ['name' => 'Pipa PVC 3/4 inch Rucika', 'unit' => 'batang', 'purchase' => 25000, 'sell' => 35000],
                ['name' => 'Pipa PVC 1 inch', 'unit' => 'batang', 'purchase' => 35000, 'sell' => 48000],
                ['name' => 'Kran Air Onda', 'unit' => 'pcs', 'purchase' => 25000, 'sell' => 38000],
            ],
            'Kayu' => [
                ['name' => 'Kayu Balok 5x10 Meranti', 'unit' => 'batang', 'purchase' => 45000, 'sell' => 65000],
                ['name' => 'Triplek 9mm', 'unit' => 'lembar', 'purchase' => 95000, 'sell' => 125000],
                ['name' => 'Papan GRC 8mm', 'unit' => 'lembar', 'purchase' => 55000, 'sell' => 75000],
            ],
            'Atap' => [
                ['name' => 'Genteng Metal Rainbow', 'unit' => 'lembar', 'purchase' => 35000, 'sell' => 48000],
                ['name' => 'Seng Gelombang BJLS', 'unit' => 'lembar', 'purchase' => 55000, 'sell' => 72000],
                ['name' => 'Asbes Gelombang', 'unit' => 'lembar', 'purchase' => 65000, 'sell' => 85000],
            ],
        ];

        $stores = Store::all();
        $codeCounter = 1;

        foreach ($categories as $categoryName => $products) {
            $category = Category::create(['name' => $categoryName]);

            foreach ($products as $p) {
                $product = Product::create([
                    'product_code' => 'PRD-' . str_pad($codeCounter++, 4, '0', STR_PAD_LEFT),
                    'name' => $p['name'],
                    'category_id' => $category->id,
                    'unit' => $p['unit'],
                    'purchase_price' => $p['purchase'],
                    'selling_price' => $p['sell'],
                ]);

                // Create inventory for each store
                foreach ($stores as $store) {
                    $stock = rand(5, 100);
                    Inventory::create([
                        'store_id' => $store->id,
                        'product_id' => $product->id,
                        'stock' => $stock,
                        'minimum_stock' => 10,
                    ]);
                }
            }
        }

        // Customers
        $customers = [
            ['name' => 'Pak Budi Santoso', 'address' => 'Jl. Merdeka No. 10, Jakarta', 'phone' => '081234567890'],
            ['name' => 'Bu Siti Rahayu', 'address' => 'Jl. Sudirman No. 25, Bandung', 'phone' => '082345678901'],
            ['name' => 'CV Bangun Jaya', 'address' => 'Jl. Industri No. 5, Surabaya', 'phone' => '083456789012'],
            ['name' => 'Pak Ahmad Wijaya', 'address' => 'Jl. Pahlawan No. 15, Semarang', 'phone' => '084567890123'],
            ['name' => 'PT Karya Mandiri', 'address' => 'Jl. Gatot Subroto No. 100, Jakarta', 'phone' => '085678901234'],
            ['name' => 'Ibu Dewi Lestari', 'address' => 'Jl. Asia Afrika No. 30, Bandung', 'phone' => '086789012345'],
            ['name' => 'Pak Hendra Gunawan', 'address' => 'Jl. Raya Bogor No. 50, Bogor', 'phone' => '087890123456'],
            ['name' => 'Toko Material Abadi', 'address' => 'Jl. Cipinang No. 22, Jakarta Timur', 'phone' => '088901234567'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
