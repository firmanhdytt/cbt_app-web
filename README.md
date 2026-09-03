# 📝 Aplikasi CBT (Computer Based Test)

[![Laravel Version](https://img.shields.io/badge/Laravel-v12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-v8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-v8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

Aplikasi Ujian Berbasis Komputer (**Computer Based Test - CBT**) berbasis web yang modern, responsif, dan aman. Membantu sekolah, institusi pendidikan, maupun lembaga pelatihan dalam menyelenggarakan ujian secara daring/luring dengan sistem penilaian otomatis, pengawasan ujian (proctoring anti-kecurangan), serta pencetakan e-sertifikat dan laporan hasil ujian.

---

## ✨ Fitur Utama

- 👨‍💼 **Multi-Role & Akses Hak Pengguna**
  - **Admin**: Manajemen pengguna (Siswa, Guru, Admin), kelas, mata pelajaran, dan konfigurasi sistem.
  - **Guru**: Manajemen bank soal (pilihan ganda, kunci jawaban, bobot nilai), penjadwalan ujian, dan evaluasi hasil.
  - **Siswa**: Mengikuti ujian interaktif, melihat riwayat nilai, dan mengunduh sertifikat kelulusan.

- 📝 **Manajemen Ujian & Bank Soal**
  - Pembuatan soal berbasis mata pelajaran & kelas.
  - Opsi pengacakan urutan soal dan pilihan jawaban.
  - Pengaturan durasi waktu (timer countdown real-time) dan jadwal mulai/selesai ujian.

- 👁️ **Sistem Pengawasan (Proctoring / Anti-Cheating)**
  - Deteksi pergerakan fokus jendela/tab peramban saat ujian berlangsung.
  - Catatan pelanggaran otomatis jika siswa mencoba membuka tab lain.

- 📊 **Penilaian & Laporan Hasil Instan**
  - Penilaian otomatis secara real-time begitu siswa menyelesaikan ujian.
  - Laporan hasil ujian siswa yang dapat diekspor dan dicetak.

- 📜 **E-Sertifikat & Cetak Dokumen (PDF)**
  - Generasi otomatis e-sertifikat kelulusan berbasis PDF (menggunakan DomPDF).
  - Cetak Kartu Peserta Ujian dan Laporan Hasil Ujian secara langsung.

- 📧 **Verifikasi Email & Keamanan**
  - Sistem verifikasi email otomatis untuk pendaftaran siswa baru menggunakan Laravel Breeze.
  - Enkripsi kata sandi dan perlindungan CSRF / XSS bawaan Laravel.

---

## 🛠️ Teknologi yang Digunakan

- **Framework Backend:** [Laravel 12](https://laravel.com)
- **Bahasa Pemrograman:** PHP >= 8.2
- **Database:** MySQL / MariaDB
- **Frontend & UI:** Blade Templates, Bootstrap 5, Tailwind CSS, Vite, JavaScript (ES6)
- **Autentikasi & Otorisasi:** Laravel Breeze & `spatie/laravel-permission`
- **Generasi PDF:** `barryvdh/laravel-dompdf`
- **Ekspor Data:** `maatwebsite/excel`

---

## ⚙️ Prasyarat Sistem

Sebelum menginstal proyek ini di mesin lokal atau server, pastikan environment Anda memenuhi prasyarat berikut:

- **PHP** >= 8.2 (dengan ekstensi `bcmath`, `ctype`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `xml`, `zip`)
- **Composer** >= 2.x
- **MySQL** >= 8.0 / MariaDB >= 10.4
- **Node.js** >= 18.x & **NPM** >= 9.x

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi di komputer lokal Anda:

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

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Database
Buka berkas `.env` lalu sesuaikan kredensial koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbt_app
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi & Seeder Database
Perintah ini akan membuat seluruh tabel, role hak akses, data contoh mata pelajaran, bank soal, dan akun uji coba:
```bash
php artisan migrate:fresh --seed
```

### 7. Tautkan Storage Link
Penting agar e-sertifikat yang disimpan di storage lokal dapat diunduh oleh siswa:
```bash
php artisan storage:link
```

### 8. Jalankan Aplikasi
Jalankan server PHP Laravel:
```bash
php artisan serve
```
Dan jalankan aset compiler Vite pada terminal terpisah:
```bash
npm run dev
```

Buka browser Anda dan akses: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Uji Coba Default (Seeder)

Anda dapat menggunakan akun-akun bawaan hasil seeder berikut untuk menguji sistem sesuai peran masing-masing:

| Peran (Role) | Alamat Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| 🛡️ **Admin** | `admin@cbt.com` | `password` | Akses penuh manajemen pengguna, kelas, mapel, soal, ujian, dan laporan. |
| 👨‍🏫 **Guru** | `guru@cbt.com` | `password` | Akses pembuatan bank soal, penjadwalan ujian, dan laporan hasil siswa. |
| 👨‍🎓 **Siswa** | `siswa@cbt.com` | `password` | Mengikuti ujian aktif, melihat riwayat nilai, dan mengunduh e-sertifikat. |

---

## 📁 Struktur Direktori Utama Proyek

```
cbt-app/
├── app/
│   ├── Http/Controllers/     # Controller Aplikasi (Admin, Guru, Siswa, Ujian, Laporan)
│   ├── Models/               # Model Eloquent (User, Mapel, Soal, Ujian, HasilUjian, dll)
│   └── Providers/
├── config/                   # Konfigurasi aplikasi, auth, permission, dll
├── database/
│   ├── migrations/           # Skema tabel database
│   └── seeders/              # Data awal (Users, Roles, Permissions, Sample Data)
├── public/                   # Asset publik & entry point index.php
├── resources/
│   ├── views/                # Template Blade (Admin, Guru, Siswa, PDF, Auth)
│   ├── css/ & js/            # Asset Frontend
├── routes/
│   ├── web.php               # Rute Web (Dashboard, Ujian, Soal, Laporan)
│   └── auth.php              # Rute Autentikasi (Breeze)
└── storage/                  # Penyimpanan log, cache, dan file e-sertifikat PDF
```

---

## 🤝 Kontribusi & Lisensi

Proyek ini bersifat terbuka di bawah lisensi [MIT License](LICENSE).
