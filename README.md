# Learning Management System (LMS) Project 📚

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)

Platform Learning Management System (LMS) modern yang dibangun untuk memfasilitasi kegiatan belajar mengajar secara digital. Sistem ini menghubungkan Admin dan Siswa (User) untuk pengelolaan kelas, materi, dan sertifikasi.

## 🚀 Fitur Utama

* **Autentikasi Aman**: Multi-level user (Admin & Peserta) dengan proteksi Middleware.
* **Manajemen Kelas & Materi**: CRUD lengkap untuk Kursus (Courses), Bab (Chapters), dan Pelajaran (Lessons).
* **Sistem Sertifikat**: Generate sertifikat otomatis dalam format PDF.
* **User Progress**: Pelacakan kemajuan belajar siswa.
* **Modern UI**: Antarmuka responsif menggunakan **Tailwind CSS**.

## 🛠️ Persyaratan Sistem (Prerequisites)

Sebelum memulai, pastikan perangkat Anda telah terinstal:

* **PHP** (Minimal versi 8.2)
* **Composer**
* **Node.js** & **NPM**
* **MySQL** (Database)
* **Git**

## ⚙️ Panduan Instalasi (Installation Guide)

### 1. Clone Repositori
Unduh source code proyek ke komputer lokal Anda.
```bash
git clone https://github.com/FarhanRangga874/Learning-Management-System-project
cd Learning-Management-System-project
```

### 2. Install Dependensi
Install library backend (Laravel) dan frontend (Vite/Tailwind).
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file konfigurasi contoh.
```bash
cp .env.example .env
```
Konfigurasi Environment (sesuaikan dengan kebutuhan)
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_pkl  # Pastikan database ini sudah dibuat di MySQL
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Konfigurasi Environment
Jalankan perintah ini untuk membuat key aplikasi dan struktur tabel database.
```bash
php artisan key:generate
php artisan migrate --seed
```
Info: Perintah --seed akan mengisi data awal (dummy) agar website siap digunakan.

### 5. Konfigurasi Penyimpanan
Langkah ini wajib agar gambar (Avatar/Thumbnail/Materi) bisa diakses publik.
```bash
php artisan storage:link
```
Info: Perintah --seed akan mengisi data awal (dummy) agar website siap digunakan.

### 5. Jalankan Aplikasi
Buka dua terminal berbeda untuk menjalankan server:
(Pastikan memakai cd "Learning-Management-System-project")

Terminal 1 (Mengaktifkan Laravel)
```bash
php artisan serve
```

Terminal 2 (Mengaktifkan Tailwind)
```bash
npm run dev
```
(Akses aplikasi di: http://127.0.0.1:8000)

## ⚙️ Mengubah User Menjadi Admin

### Opsi 1: Menggunakan Laravel Tinker (Disarankan)
Cara ini paling cepat karena menggunakan command line.

### 1. Buka terminal di dalam folder project, lalu ketik:
```bash
php artisan tinker
```

### 2. Cari user berdasarkan email, ubah role-nya, lalu simpan. Ketik baris berikut satu per satu
```bash
$user = \App\Models\User::where('email', 'email_anda@contoh.com')->first();
$user->role = 'admin';
$user->save();
exit
```
(Ganti 'email_anda@contoh.com' dengan email yang ingin dijadikan admin).

### Opsi 2: Menggunakan localhost
1. Buka phpMyAdmin (biasanya di http://localhost/phpmyadmin).
2. Pilih database lms_pkl.
3. Klik tabel users.
4. Cari baris user yang ingin diubah, klik Edit.
5. Pada kolom role, ubah nilainya dari 'enroll' menjadi 'admin'.
6. Klik Go / Simpan.

## 🐋 Docker Setup
*Pastikan Docker Desktop sudah terinstall dan berjalan (Running).

### 1. Install Dependencies (Initial Setup)
Karena folder vendor belum ada saat pertama kali di-clone, gunakan perintah Docker ini untuk menginstall library yang dibutuhkan:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
(Perintah ini akan meminjam container PHP sementara untuk mendownload semua library Laravel).

### 2. Konfigurasi Environment
Salin file konfigurasi
```bash
cp .env.example .env
```
*Buka file .env dan ubah konfigurasi Database agar sesuai dengan Docker (Sail):
```bash
DB_CONNECTION=mysql
DB_HOST=mysql      <-- Ubah ini (jangan 127.0.0.1)
DB_PORT=3306
DB_DATABASE=lms_pkl
DB_USERNAME=sail   <-- User default Sail
DB_PASSWORD=password
```

### 3.Jalankan container
Jalankan perintah ini untuk menyalakan server:
```bash
./vendor/bin/sail up -d
```

### 4. Setup Aplikasi
Jalankan perintah berikut menggunakan prefix ./vendor/bin/sail (bukan php):
```bash
# Generate Key
./vendor/bin/sail artisan key:generate

# Migrasi & Seeding Database
./vendor/bin/sail artisan migrate --seed

# Link Storage (Agar gambar muncul)
./vendor/bin/sail artisan storage:link
```

### 5. Setup Frontend (Tailwind CSS)
Install dan jalankan Vite server untuk style:
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

## ⚙️ Mengubah User Menjadi Admin (Docker)
### 1. Buka terminal di dalam folder project, lalu ketik:
```bash
./vendor/bin/sail artisan tinker
```

### 2. Cari user berdasarkan email, ubah role-nya, lalu simpan. Ketik baris berikut satu per satu
```bash
$user = \App\Models\User::where('email', 'email_anda@contoh.com')->first();
$user->role = 'admin';
$user->save();
exit
```
(Ganti 'email_anda@contoh.com' dengan email yang ingin dijadikan admin).

