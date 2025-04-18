# Aplikasi Manajemen Kepegawaian (CodeIgniter 3)

Ini adalah aplikasi web yang dibangun menggunakan framework PHP CodeIgniter 3 untuk membantu mengelola data dan proses administrasi kepegawaian dalam suatu organisasi atau perusahaan.

## Deskripsi Singkat

Aplikasi ini bertujuan untuk mempermudah pengelolaan informasi karyawan, mulai dari data pribadi, jabatan, absensi (jika ada), hingga penggajian (jika ada). Aplikasi ini dirancang agar mudah digunakan dan dikelola.

## Fitur Utama

* **Manajemen Data Karyawan:** CRUD (Create, Read, Update, Delete) untuk data karyawan (data pribadi, kontak, informasi pekerjaan, dll.).
* **Manajemen Jabatan/Posisi:** Pengelolaan daftar jabatan atau posisi yang ada di perusahaan.
* **Manajemen Departemen/Unit Kerja:** Pengelolaan daftar departemen atau unit kerja.
* **Manajemen Pengguna (User Management):** Pengelolaan akun pengguna aplikasi (misalnya: Admin, HRD, Karyawan) beserta hak aksesnya.
* **Autentikasi:** Sistem login dan logout yang aman.
* **[Opsional] Manajemen Absensi:** Pencatatan kehadiran karyawan.
* **[Opsional] Manajemen Cuti:** Pengajuan dan persetujuan cuti karyawan.
* **[Opsional] Pelaporan:** Pembuatan laporan terkait data kepegawaian.
* *(Tambahkan fitur-fitur spesifik lainnya yang ada di aplikasi Anda)*

## Kebutuhan Sistem

* PHP versi 5.6 atau lebih baru (disarankan PHP 7.x untuk performa lebih baik).
* Web Server (Apache direkomendasikan, Nginx juga bisa).
* Database (MySQL / MariaDB direkomendasikan).
* Composer (jika Anda menggunakan dependensi pihak ketiga yang dikelola oleh Composer).
* Browser Web (Chrome, Firefox, Safari, Edge, dll.).

## Instalasi

Berikut adalah langkah-langkah untuk menginstal dan menjalankan aplikasi ini di lingkungan lokal:

1.  **Clone Repository:**
    ```bash
    git clone [URL_REPOSITORY_ANDA] nama_folder_proyek
    cd nama_folder_proyek
    ```
    Atau, jika Anda tidak menggunakan Git, cukup salin folder proyek ke direktori web server Anda (misalnya: `htdocs` untuk XAMPP, `www` untuk WAMP).

2.  **Konfigurasi Database:**
    * Buat database baru di MySQL/MariaDB Anda (misalnya: `db_kepegawaian_ci3`).
    * Impor struktur dan data awal dari file SQL yang disediakan (misalnya: `database.sql`) ke database yang baru Anda buat. Cari file `.sql` dalam proyek Anda.
        ```bash
        mysql -u [username_db] -p [nama_database] < database.sql
        ```
    * Salin file konfigurasi database `application/config/database.php.example` (jika ada) menjadi `application/config/database.php` atau langsung edit file `application/config/database.php`.
    * Sesuaikan konfigurasi database di `application/config/database.php` dengan detail koneksi database Anda:
        ```php
        'hostname' => 'localhost',
        'username' => 'root', // Ganti dengan username database Anda
        'password' => '', // Ganti dengan password database Anda
        'database' => 'db_kepegawaian_ci3', // Ganti dengan nama database Anda
        'dbdriver' => 'mysqli',
        ```

3.  **Konfigurasi Base URL:**
    * Buka file `application/config/config.php`.
    * Sesuaikan `$config['base_url']` dengan URL tempat Anda meletakkan aplikasi:
        ```php
        // Contoh jika diakses melalui http://localhost/nama_folder_proyek/
        $config['base_url'] = 'http://localhost/nama_folder_proyek/';
        ```
    * Pastikan juga `$config['index_page']` diatur sesuai kebutuhan (biasanya dikosongkan `''` jika Anda menggunakan `.htaccess` untuk menghilangkan `index.php` dari URL, atau biarkan `'index.php'` jika tidak).

4.  **Konfigurasi Web Server (Apache):**
    * Jika Anda ingin menghilangkan `index.php` dari URL (URL yang lebih bersih), pastikan modul `mod_rewrite` di Apache aktif.
    * Gunakan file `.htaccess` yang sesuai di root direktori proyek Anda. Contoh `.htaccess` standar untuk CodeIgniter 3:
        ```apache
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php/$1 [L]
        ```

5.  **Instal Dependensi (Jika menggunakan Composer):**
    * Jika proyek Anda memiliki file `composer.json`, jalankan perintah berikut di terminal dari direktori root proyek:
        ```bash
        composer install
        ```

6.  **Selesai!**
    * Buka browser dan akses URL yang telah Anda konfigurasi pada `base_url` (misalnya: `http://localhost/nama_folder_proyek/`).

## Penggunaan

* Akses aplikasi melalui URL yang telah dikonfigurasi.
* Login menggunakan akun default (jika ada, sebutkan di sini, contoh: `admin` / `password`).
    * **Penting:** Segera ganti password default setelah login pertama kali demi keamanan.
* Jelajahi menu-menu yang tersedia untuk mengelola data kepegawaian.

## Struktur Folder (Penting)

* `application/`: Berisi semua kode aplikasi Anda (controllers, models, views, config, libraries, helpers).
* `system/`: Berisi inti (core) dari framework CodeIgniter 3. Sebaiknya jangan diubah.
* `vendor/`: Berisi dependensi yang diinstal oleh Composer (jika digunakan).

## Teknologi yang Digunakan

* PHP
* CodeIgniter 3 Framework
* MySQL / MariaDB
* HTML
* CSS (Tailwind CDN)
* JavaScript (Jquery, Alpine JS,)

## Kontribusi

Jika Anda ingin berkontribusi pada pengembangan aplikasi ini, silakan ikuti langkah-langkah berikut:
1.  Fork repository ini.
2.  Buat branch baru (`git checkout -b fitur-baru`).
3.  Lakukan perubahan atau penambahan fitur Anda.
4.  Commit perubahan Anda (`git commit -m 'Menambahkan fitur X'`).
5.  Push ke branch Anda (`git push origin fitur-baru`).
6.  Buat Pull Request baru.

## Lisensi

(Sebutkan lisensi yang Anda gunakan untuk proyek ini, contoh: MIT License, Apache License 2.0, atau lisensi kustom). Jika tidak ada, Anda bisa menulis "Hak Cipta [Tahun] [Nama Anda/Organisasi Anda]. Hak cipta dilindungi undang-undang."

---

**Catatan:**
* Ganti semua teks yang ada di dalam kurung siku `[` dan `]` dengan informasi yang sesuai dengan proyek Anda.
* Tambahkan atau hapus bagian sesuai dengan relevansi dan fitur aplikasi Anda.
* Pastikan file `database.sql` (atau nama lainnya) memang ada dan berisi struktur database yang benar.
* Jika ada langkah konfigurasi tambahan (misalnya: pengaturan API key, konfigurasi email), jangan lupa menambahkannya di bagian Instalasi.

Semoga README ini membantu!