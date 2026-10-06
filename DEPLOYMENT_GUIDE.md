# Panduan Deployment - Fix Used Gear di Hosting

## Masalah
Produk dengan kondisi "used" tidak muncul di halaman `/used-gear` di hosting, padahal di local sudah berfungsi.

## Penyebab
Migration untuk menambahkan 'used' ke ENUM kolom `kondisi` belum dijalankan di database hosting.

## Solusi

### Langkah 1: Pastikan File Migration Sudah Di-upload
Pastikan file migration berikut sudah ada di hosting:
```
database/migrations/2025_12_22_041709_add_used_to_kondisi_enum_in_landing_page_products_table.php
```

### Langkah 2: Jalankan Migration di Hosting

**Via SSH (Recommended):**
```bash
# Masuk ke direktori project di hosting
cd /path/to/your/project

# Jalankan migration
php artisan migrate --force
```

**Via cPanel Terminal (jika tersedia):**
```bash
php artisan migrate --force
```

**Via PHPMyAdmin (Alternatif jika tidak bisa SSH):**
1. Buka PHPMyAdmin di hosting
2. Pilih database yang digunakan
3. Buka tab SQL
4. Jalankan query berikut:
```sql
ALTER TABLE landing_page_products MODIFY COLUMN kondisi ENUM('great', 'good', 'used') NOT NULL;
```

### Langkah 3: Clear Cache (Opsional tapi Disarankan)
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Langkah 4: Verifikasi
1. Cek di admin panel apakah produk dengan kondisi "Used" bisa ditambahkan
2. Cek di halaman `/used-gear` apakah produk muncul

## Catatan Penting
- Flag `--force` diperlukan untuk menjalankan migration di production environment
- Pastikan backup database dilakukan sebelum menjalankan migration
- Jika ada error, cek log di `storage/logs/laravel.log`


