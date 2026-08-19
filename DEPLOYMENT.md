# Panduan Instalasi dan Deployment Aplikasi CBT

Aplikasi Computer Based Test (CBT) ini dibangun menggunakan framework **Laravel 12**, **MySQL**, **Laravel Breeze (Blade)**, **Bootstrap 5**, dan beberapa package pendukung seperti **spatie/laravel-permission**, **maatwebsite/excel**, dan **barryvdh/laravel-dompdf**.

---

## 1. Prasyarat Sistem
Sebelum memulai instalasi, pastikan server atau mesin lokal Anda memenuhi spesifikasi berikut:
* **PHP** >= 8.2 (dengan ekstensi `bcmath`, `ctype`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `xml`, `zip`)
* **Composer** >= 2.x
* **MySQL** >= 8.0 atau MariaDB >= 10.4
* **Node.js** >= 18.x & **NPM** >= 9.x

---

## 2. Instalasi Lokal & Pengembangan
Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal:

1. **Clone/Buka Folder Proyek**
   Masuk ke direktori utama proyek `cbt-app`.

2. **Salin Berkas Konfigurasi Lingkungan (`.env`)**
   ```bash
   cp .env.example .env
   ```

3. **Instal Dependensi PHP (Composer)**
   ```bash
   composer install
   ```

4. **Instal Dependensi Javascript (NPM)**
   ```bash
   npm install
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database pada Berkas `.env`**
   Buka berkas `.env` dan sesuaikan koneksi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=username_mysql_anda
   DB_PASSWORD=password_mysql_anda
   ```

7. **Jalankan Migrasi & Seeder Database**
   Perintah ini akan membuat seluruh tabel, hak akses (roles/permissions), mata pelajaran, bank soal, dan akun uji coba:
   ```bash
   php artisan migrate:fresh --seed
   ```

8. **Tautkan Storage Link**
   Penting agar e-sertifikat yang disimpan di storage lokal dapat diunduh oleh siswa secara aman:
   ```bash
   php artisan storage:link
   ```

9. **Jalankan Server Pengembang (Lokal)**
   Jalankan server PHP Laravel:
   ```bash
   php artisan serve
   ```
   Dan jalankan compiler aset Vite di terminal terpisah:
   ```bash
   npm run dev
   ```

Aplikasi kini dapat diakses di peramban (browser) melalui URL: **`http://127.0.0.1:8000`**

---

## 3. Akun Pengguna Uji Coba (Default Seeders)
Anda dapat menggunakan akun-akun bawaan berikut untuk menguji sistem sesuai role masing-masing:

| Peran (Role) | Alamat Email | Kata Sandi (Password) | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@cbt.com` | `password` | Akses penuh manajemen pengguna, mapel, soal, ujian, dan laporan. |
| **Guru** | `guru@cbt.com` | `password` | Akses pembuatan soal, pembuatan jadwal ujian, dan melihat laporan. |
| **Siswa** | `siswa@cbt.com` | `password` | Mengikuti ujian aktif, melihat riwayat nilai, unduh sertifikat. |

---

## 4. Konfigurasi Email & Verifikasi (SMTP)
Aplikasi mengimplementasikan `MustVerifyEmail` untuk keaslian pendaftaran siswa baru. Untuk menghubungkan pengiriman email dengan SMTP (misalnya Mailtrap atau Gmail), sesuaikan baris berikut di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io # Ubah sesuai host SMTP Anda
MAIL_PORT=2525
MAIL_USERNAME=username_smtp_anda
MAIL_PASSWORD=password_smtp_anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cbtportal.com"
MAIL_FROM_NAME="CBT Portal Engine"
```

---

## 5. Panduan Deployment Produksi (Ubuntu Server VPS)
Ketika mendeploy aplikasi ke production (server VPS/Cloud):

1. **Arahkan Web Server Virtual Host (Nginx / Apache)**
   Arahkan root direktori dokumen domain Anda ke folder **`/public`** dari proyek Laravel (contoh: `/var/www/cbt-app/public`), bukan ke folder root proyek utama.

2. **Kumpulkan & Optimasi Dependensi**
   Jalankan perintah ini di server produksi untuk menghemat performa:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Build Aset Frontend**
   Kompilasi aset CSS dan Javascript agar siap digunakan di produksi:
   ```bash
   npm run build
   ```

4. **Atur Hak Akses File (Permissions)**
   Pastikan direktori `storage` dan `bootstrap/cache` dapat ditulis oleh web server (`www-data`):
   ```bash
   sudo chown -R www-data:www-data /var/www/cbt-app
   sudo chmod -R 775 /var/www/cbt-app/storage /var/www/cbt-app/bootstrap/cache
   ```

5. **Aktifkan HTTPS (SSL)**
   Gunakan Certbot Let's Encrypt untuk mengamankan pengiriman data jawaban ujian siswa melalui jalur HTTPS terenkripsi.
