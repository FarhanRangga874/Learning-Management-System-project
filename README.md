<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<h1 align="center">Learning Management System (LMS) Project</h1>

<p align="center">
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP Version"></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel Version"></a>
    <a href="#"><img src="https://img.shields.io/badge/License-MIT-blue?style=flat-square" alt="License"></a>
</p>

<p align="center">
  <b>Aplikasi manajemen pembelajaran berbasis web yang modern dan terintegrasi.</b>
</p>

---

## 📝 Deskripsi Proyek

**Learning Management System (LMS)** ini adalah platform berbasis Laravel yang dirancang untuk memfasilitasi kegiatan belajar mengajar. Sistem ini menghubungkan **Admin** dan **Pengguna** untuk pengelolaan kelas, materi pelajaran, dan administrasi akademik secara efisien.

### 🌟 Fitur Utama
| Fitur | Deskripsi |
| :--- | :--- |
| 🔐 **Auth** | Login Multi-user (Admin dan Pengguna). |
| 📚 **Kelas** | CRUD Kursus dan Materi Pelajaran. |
| 📊 **Dashboard** | Statistik ringkas untuk monitoring data. |
| 👥 **Users** | Manajemen data pengguna yang lengkap. |

---

## 🚀 Panduan Instalasi & Penggunaan

Pastikan Anda sudah menginstall **PHP, Composer, Node.js, dan MySQL**. Jalankan perintah di bawah ini secara berurutan di terminal:

```bash
# 1. Download & Masuk Folder
git clone [https://github.com/FarhanRangga874/Learning-Management-System-project.git](https://github.com/FarhanRangga874/Learning-Management-System-project.git)
cd Learning-Management-System-project

# 2. Install Library (Backend & Frontend)
composer install
npm install

# 3. Setup Konfigurasi
cp .env.example .env
php artisan key:generate

# --- PENTING: STOP SEBENTAR ---
# Buka file .env di text editor, lalu ubah bagian ini:
# DB_DATABASE=lms_pkl
# (Pastikan database 'lms_pkl' sudah dibuat di MySQL)

# 4. Buat Tabel Database
php artisan migrate

# 5. Menjalankan Aplikasi
# Buka 2 Terminal berbeda dan jalankan perintah ini:

# Terminal A:
php artisan serve

# Terminal B:
npm run dev
