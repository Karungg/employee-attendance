# Attendly - Sistem Absensi Karyawan
![Build Status](https://img.shields.io/github/actions/workflow/status/[username]/[repo]/laravel.yml?branch=main)
![License](https://img.shields.io/github/license/[username]/[repo])

> Sebuah aplikasi web modern untuk mengelola absensi karyawan, memantau jam masuk dan keluar, serta menghasilkan laporan kehadiran. Dibuat dengan Laravel.

![Screenshot Aplikasi](https://via.placeholder.com/800x450.png?text=Screenshot+Aplikasi+Anda)

---

##  Daftar Isi
* [Tentang Proyek](#tentang-proyek)
* [Fitur Utama](#fitur-utama)
* [Teknologi yang Digunakan](#teknologi-yang-digunakan)
* [Instalasi & Persiapan](#instalasi--persiapan)
* [Cara Penggunaan](#cara-penggunaan)
* [Lisensi](#lisensi)
* [Kontak](#kontak)

---

## Tentang Proyek
Proyek ini dibuat untuk mengatasi tantangan dalam manajemen kehadiran karyawan secara manual. Dengan sistem terpusat ini, HR atau manajer dapat dengan mudah melacak jam kerja, keterlambatan, dan riwayat absensi setiap karyawan secara *real-time*.

---

## ✨ Fitur Utama
* **Manajemen Karyawan:** Tambah, edit, dan hapus data karyawan.
* **Manajemen Departemen:** Kelola departemen dan tetapkan batas waktu jam masuk.
* **Absensi (Clock In / Clock Out):** Karyawan dapat melakukan absensi masuk dan keluar.
* **Riwayat Absensi:** Lihat dan filter riwayat absensi per karyawan atau per tanggal.
* **Deteksi Keterlambatan:** Sistem secara otomatis mendeteksi jika karyawan terlambat.
* **Laporan Kehadiran:** (Opsional) Ekspor data absensi ke format CSV atau PDF.
* **Role & Permission:** (Opsional) Peran yang berbeda untuk Admin dan Karyawan.

---

## 🛠️ Teknologi yang Digunakan
* **Backend:** PHP 8.2, Laravel 11
* **Frontend:** Blade, Tailwind CSS
* **Database:** MySQL / PostgreSQL
* **Admin Panel:** Filament
---

## 🚀 Instalasi & Persiapan
Ikuti langkah-langkah berikut untuk menjalankan proyek ini secara lokal.

1.  **Clone repositori**
    ```bash
    git clone [https://github.com/](https://github.com/)[username]/[nama-repo].git
    cd [nama-repo]
    ```

2.  **Install dependensi Composer**
    ```bash
    composer install
    ```

3.  **Buat file `.env`**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

4.  **Generate application key**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database**
    Buka file `.env` dan sesuaikan pengaturan database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

6.  **Jalankan Migrasi & Seeder**
    Ini akan membuat semua tabel database dan mengisi data awal yang diperlukan.
    ```bash
    php artisan migrate --seed
    ```

7.  **Jalankan server lokal**
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang berjalan di `http://127.0.0.1:8000`.

8.  **Akun Default**
    * **Email:** `admin@example.com`
    * **Password:** `password`

---

## 📝 Cara Penggunaan
Setelah login, Anda dapat mulai mengelola data master seperti departemen dan karyawan. Karyawan yang login dapat langsung melakukan absensi pada halaman dasbor.

---

## 📄 Lisensi
Proyek ini dilisensikan di bawah Lisensi MIT. Lihat file `LICENSE` untuk detailnya.

---

## 📧 Kontak
[Nama Anda] - [emailanda@example.com]

Link Proyek: [https://github.com/[username]/[nama-repo]](https://github.com/[username]/[nama-repo])