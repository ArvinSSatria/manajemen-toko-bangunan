# Manajemen Toko Bangunan

Aplikasi web Manajemen Toko Bangunan yang komprehensif, dibangun dengan menggunakan framework [Laravel 12](https://laravel.com/). Aplikasi ini dirancang untuk mempermudah operasional sehari-hari toko material/bangunan, mulai dari manajemen inventaris, kasir (POS), arus kas, hingga pelaporan.

## 🚀 Fitur Utama

- **Manajemen Inventaris & Produk:** Kelola data produk, kategori, dan stok gudang.
- **Point of Sale (Kasir/POS):** Sistem kasir yang cepat dan responsif untuk transaksi penjualan, termasuk fitur cetak struk.
- **Manajemen Pelanggan & Piutang:** Lacak data pelanggan, batas kredit, deposit, dan tagihan/piutang pelanggan.
- **Manajemen Multi-Toko (Cabang):** Dukungan untuk mengelola beberapa toko sekaligus beserta transfer stok antar toko.
- **Arus Kas & Keuangan:** Catat pemasukan (Income) dan pengeluaran (Expense) operasional toko secara detail.
- **Manajemen Pembelian (Backorder):** Pencatatan pembelian barang dari supplier.
- **Laporan (Reporting):** Generate laporan penjualan, inventaris, dan keuangan dalam format PDF.
- **Manajemen Pengguna & Hak Akses:** Sistem role-based access control (RBAC) menggunakan Spatie Permission (Admin, Kasir, Manajer, dll).

## 🛠️ Tech Stack

- **Backend:** Laravel 12.x (PHP 8.2+)
- **Frontend:** Laravel Blade, Tailwind CSS (via Breeze)
- **Database:** MySQL / PostgreSQL
- **Role Management:** Spatie Laravel Permission
- **Authentication:** Laravel Breeze

## ⚙️ Cara Instalasi & Menjalankan di Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer lokal (localhost):

1. **Clone repository ini**
   ```bash
   git clone https://github.com/ArvinSSatria/manajemen-toko-bangunan.git
   cd manajemen-toko-bangunan
   ```

2. **Install dependency PHP (Composer)**
   ```bash
   composer install
   ```

3. **Install dependency Node.js (NPM)**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database Anda di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=manajemen_toko
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Seeder Database**
   Perintah ini akan membuat tabel dan mengisi data awal (dummy data) beserta peran (roles) dan akun admin.
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan Server Development**
   Untuk kemudahan, Anda bisa menggunakan script yang sudah disediakan:
   ```bash
   npm run dev
   ```
   Atau jika ingin menjalankan secara terpisah:
   ```bash
   php artisan serve
   ```
   Dan jalankan Vite di terminal baru:
   ```bash
   npm run dev
   ```

8. **Akses Aplikasi**
   Buka browser dan akses `http://localhost:8000`.

## 👤 Kredensial Akses Default

Jika Anda telah menjalankan seeder, gunakan kredensial berikut untuk masuk sebagai administrator utama:

- **Email:** admin@admin.com (atau sesuaikan dengan `AdminUserSeeder`)
- **Password:** password

## 📄 Lisensi

Aplikasi ini menggunakan lisensi [MIT license](https://opensource.org/licenses/MIT).
