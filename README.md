# Web Daily Journal

Web Daily Journal adalah aplikasi sederhana untuk mengelola artikel harian. Proyek ini dibangun menggunakan PHP dan MySQL, serta Bootstrap untuk desain antarmuka.

## Fitur Utama
- **Login Admin**: Hanya pengguna dengan kredensial admin yang dapat mengubah artikel.
- **CRUD Artikel**: Tambah, ubah, hapus, dan lihat artikel.
- **UI Responsif**: Menggunakan Bootstrap untuk mendukung tampilan di berbagai perangkat.

---

## Cara Mengakses dan Mengelola Artikel

1. **Login sebagai Admin**:
   - Buka halaman login di `login.php`.
   - Masukkan kredensial berikut:
     - **Username**: `admin`
     - **Password**: `123456`
   - Klik tombol **Login**.

2. **Mengubah Artikel**:
   - Setelah login, Anda akan diarahkan ke halaman dashboard (`admin.php`).
   - Pada dashboard, Anda bisa:
     - Melihat daftar artikel.
     - Mengubah artikel yang sudah ada.
     - Menambahkan artikel baru.
     - Menghapus artikel yang tidak diperlukan.

3. **Logout**:
   - Klik tombol **Logout** di dashboard untuk keluar dari akun admin.

---

## Cara Menjalankan Proyek di Lokal

### Prasyarat
- PHP versi terbaru
- MySQL/MariaDB
- Server lokal seperti XAMPP, WAMP, atau LAMP

### Langkah-langkah
1. Clone repository ini ke direktori lokal:
   ```bash
   git clone https://github.com/petrayosi/pbw2025.git
