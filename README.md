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
git clone [https://github.com/afrizal/Learning-Management-System-project.git](https://github.com/afrizal/Learning-Management-System-project.git)
cd Learning-Management-System-project
2. Install Dependensi
Install library backend (Laravel) dan frontend (Vite/Tailwind).

Bash
composer install
npm install
3. Konfigurasi Environment
Salin file konfigurasi contoh.

Bash
cp .env.example .env
Buka file .env dan sesuaikan pengaturan database:

Cuplikan kode
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_pkl  # Pastikan database ini sudah dibuat di MySQL
DB_USERNAME=root
DB_PASSWORD=
4. Generate Key & Database
Jalankan perintah ini untuk membuat key aplikasi dan struktur tabel database.

Bash
php artisan key:generate
php artisan migrate --seed
Info: Perintah --seed akan mengisi data awal (dummy) agar website siap digunakan.

5. Jalankan Aplikasi
Buka dua terminal berbeda untuk menjalankan server:

Terminal A (Laravel Server):

Bash
php artisan serve
Terminal B (Vite Development):

Bash
npm run dev
Akses aplikasi di: http://127.0.0.1:8000

👑 Cara Mengubah User Menjadi Admin
Secara default, user baru yang mendaftar (Register) akan memiliki role 'enroll' (Siswa). Untuk mengubah user menjadi Admin, Anda harus mengubahnya secara manual di database. Berikut caranya:

Opsi 1: Menggunakan Laravel Tinker (Disarankan)
Cara ini paling cepat karena menggunakan command line.

Buka terminal di dalam folder project, lalu ketik:

Bash
php artisan tinker
Cari user berdasarkan email, ubah role-nya, lalu simpan. Ketik baris berikut satu per satu:

PHP
$user = \App\Models\User::where('email', 'email_anda@contoh.com')->first();
$user->role = 'admin';
$user->save();
exit
(Ganti 'email_anda@contoh.com' dengan email yang ingin dijadikan admin).

Opsi 2: Menggunakan phpMyAdmin (GUI)
Buka phpMyAdmin (biasanya di http://localhost/phpmyadmin).

Pilih database lms_pkl.

Klik tabel users.

Cari baris user yang ingin diubah, klik Edit.

Pada kolom role, ubah nilainya dari 'enroll' menjadi 'admin'.

Klik Go / Simpan.

🔑 Akun Demo (Default)
Jika Anda menjalankan Seeder, berikut adalah akun default yang tersedia:

Email: test@example.com

Password: password

Role: enroll (Ubah ke admin menggunakan panduan di atas jika perlu)

🐛 Troubleshooting
Vite manifest not found: Pastikan npm run dev sedang berjalan.

Access Denied (403): Jika Anda tidak bisa mengakses dashboard admin, pastikan kolom role di database tabel users sudah bernilai admin.

Database error: Pastikan nama database di file .env sama dengan yang ada di MySQL.

📄 Lisensi
Project ini dilisensikan di bawah MIT license.


### Apa yang ditambahkan?
Berdasarkan file `create_users_table.php` dan `IsAdmin.php`, sistem Anda menggunakan kolom `role` dengan nilai Enum `'admin'` atau `'enroll'`.

Bagian **"👑 Cara Mengubah User Menjadi Admin"** di atas memberikan instruksi spesifik untuk mengubah nilai kolom tersebut. Saya memberikan dua opsi:
1.  **Tinker:** Lebih "programmer way" dan tidak perlu buka browser tambahan.
2.  **phpMyAdmin:** Lebih visual dan mudah dipahami jika belum terbiasa dengan CLI.
