# 🏫 CBT Portal - SMA Negeri 5 Medan

[![Laravel Version](https://img.shields.io/badge/Laravel-v12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-v8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-v8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Flutter](https://img.shields.io/badge/Flutter-v3.x-02569B?style=for-the-badge&logo=flutter)](https://flutter.dev)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

Aplikasi Portal Ujian Berbasis Komputer & Smartphone (**Computer Based Test - CBT**) resmi untuk **SMA Negeri 5 Medan**. Sistem ini mengintegrasikan portal web modern berbasis Laravel dengan aplikasi mobile pengunci **Exambro Kiosk App (Flutter)** untuk menyelenggarakan ujian sekolah yang aman, profesional, dan fully-responsive.

---

## ✨ Fitur-Fitur Unggulan

- 🏛️ **Profil Identitas Resmi SMA Negeri 5 Medan**
  - Branding sekolah lengkap (Logo SMAN 5 Medan, Favicon, Kop Surat PDF, dan Sertifikat Kelulusan).
  - Tampilan Landing Page & Login yang responsif dan modern.

- 📱 **Integrasi Exambro Mobile App (Flutter)**
  - Aplikasi Android Kiosk pengunci layar ujian khusus smartphone.
  - Icon peluncur Android berlogo SMAN 5 Medan dengan label nama `CBT SMAN 5 Medan`.

- 🛡️ **Sistem Pengawasan Proctoring Ketat (Anti-Kecurangan)**
  - Deteksi otomatis pembatalan layar penuh (Fullscreen) dan perpindahan tab/jendela browser.
  - Logika sanksi 2 kali peringatan (*2-strike violation policy*) dengan penguncian ujian otomatis dan fitur pengajuan permohonan buka kunci.

- 👨‍💼 **Multi-Role & Akses Pengguna**
  - **Admin**: Kelola pengguna (Siswa, Guru, Admin), kelas terstruktur, mata pelajaran, serta fitur **Import Pengguna via Excel/CSV**.
  - **Guru**: Pengelolaan bank soal (pilihan ganda, opsi A-D, kunci jawaban), pembuatan jadwal ujian, dan evaluasi nilai.
  - **Siswa**: Ruang ujian interaktif dengan navigasi peta soal, timer hitung mundur bulat, riwayat nilai, dan unduh e-sertifikat.

- 📊 **Struktur Detail Kelas & Import Excel Pengguna**
  - Halaman detail kelas (`admin/kelas/show`) menampilkan daftar siswa terdaftar, statistik kelas, dan jadwal ujian khusus kelas.
  - Fitur **Import Excel Data Pengguna** dilengkapi template unduhan Excel otomatis (`UserTemplateExport`).

- 📜 **Penerbitan E-Sertifikat & QR Code Verification**
  - Generasi otomatis PDF E-Sertifikat Kelulusan (*on-the-fly*) untuk siswa yang memenuhi KKM (>= 70).
  - Dilengkapi QR Code unik untuk verifikasi keaslian dokumen secara publik.

---

## 🛠️ Teknologi yang Digunakan

- **Backend Framework:** [Laravel 12](https://laravel.com)
- **Bahasa Pemrograman:** PHP >= 8.2 & Dart/Flutter (Mobile)
- **Database:** MySQL / MariaDB
- **Frontend & UI:** Blade Templates, Bootstrap 5, Tailwind CSS, JavaScript (ES6)
- **Autentikasi & Hak Akses:** Laravel Breeze & `spatie/laravel-permission`
- **PDF & QR Code Generator:** `barryvdh/laravel-dompdf` & `simplesoftwareio/simple-qrcode`
- **Ekspor & Impor Excel:** `maatwebsite/excel`

---

## ⚙️ Prasyarat Sistem

- **PHP** >= 8.2 (dengan ekstensi `pdo_mysql`, `gd`, `zip`, `mbstring`, `openssl`, `fileinfo`)
- **Composer** >= 2.x
- **MySQL** >= 8.0 / MariaDB >= 10.4
- **Node.js** >= 18.x & **NPM** >= 9.x

---

## 🚀 Panduan Instalasi & Pengoperasian Lokal

### 1. Clone Repository & Masuk ke Folder Proyek
```bash
git clone https://github.com/firmanhdytt/Web5-cbt_app.git
cd Web5-cbt_app/cbt-app
```

### 2. Salin Berkas `.env`
```bash
cp .env.example .env
```

### 3. Instal Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### 4. Generate Application Key & Storage Link
```bash
php artisan key:generate
php artisan storage:link
```

### 5. Konfigurasi Database `.env`
Sesuaikan kredensial database Anda pada berkas `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbt_app
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi & Seeder Database
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Aplikasi
Jalankan server PHP Laravel agar dapat diakses oleh perangkat HP dalam jaringan WiFi yang sama:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
Dan jalankan compiler Vite:
```bash
npm run dev
```

Buka browser dan akses: **`http://localhost:8000`** atau via IP lokal laptop Anda (contoh: **`http://192.168.1.x:8000`**).

---

## 🔑 Akun Uji Coba Default (Seeder)

| Peran (Role) | Email / Username | Password | Deskripsi Akses |
| :--- | :--- | :--- | :--- |
| 🛡️ **Admin** | `admin@cbt.com` / `admin` | `password` | Akses penuh manajemen pengguna, kelas, mapel, soal, ujian, import excel, dan laporan. |
| 👨‍🏫 **Guru** | `guru@cbt.com` / `guru` | `password` | Akses pembuatan bank soal, penjadwalan ujian, dan pengawasan hasil siswa. |
| 👨‍🎓 **Siswa** | `siswa@cbt.com` / `siswa` | `password` | Mengikuti ujian aktif, melihat riwayat nilai, dan mengunduh e-sertifikat. |

---

## 🤝 Lisensi

Proyek ini dikembangkan secara resmi untuk SMA Negeri 5 Medan dan terlisensi di bawah [MIT License](LICENSE).
