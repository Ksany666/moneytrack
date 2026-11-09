# 🛡️ MoneyTrack - Secure Personal Finance

Sistem Informasi Manajemen Keuangan Pribadi Berbasis Web yang mengimplementasikan kriptografi simetris **AES-256-GCM** (Advanced Encryption Standard - Galois/Counter Mode).

Proyek ini dibuat untuk memenuhi Tugas Mata Kuliah **Keamanan Data dan Informasi** di Program Studi Teknik Informatika, Universitas Muhammadiyah Sukabumi.
- **Pengembang:** Afdhal Tsany Nurrizki

## 🔐 Fitur Keamanan
1. **Private Key Authentication:** Tidak menggunakan *password hashing* konvensional. Login dilakukan dengan menguji *Private Key* untuk mendekripsi *ciphertext* di database. Kunci tidak pernah disimpan di dalam *database*.
2. **Authenticated Encryption (GCM):** Memastikan kerahasiaan data (enkripsi nominal dan keterangan transaksi) sekaligus memastikan integritas data (data tidak bisa dimanipulasi dari *database* karena dilindungi oleh *GCM Authentication Tag*).
3. **Real-time Decryption:** Proses kalkulasi saldo dan diagram Chart.js dilakukan secara *on-the-fly* pada sisi server (*session*) tanpa menyimpan data asli.

## 🛠️ Teknologi yang Digunakan
- **Backend:** PHP 8 (Native)
- **Database:** MariaDB (Laragon)
- **Kriptografi:** OpenSSL PHP (`aes-256-gcm`)
- **Frontend:** HTML5, CSS3, Bootstrap 5, Chart.js, FontAwesome

## 🚀 Cara Instalasi
1. Clone repositori ini: `git clone https://github.com/username/moneytrack-aes256.git`
2. Pindahkan folder ke direktori server lokal (misal: `C:\laragon\www\moneytrack`).
3. Buat database baru di phpMyAdmin dengan nama `db_moneytrack`.
4. Import file `database/db_moneytrack.sql` ke dalam database tersebut.
5. Sesuaikan koneksi di `config/koneksi.php` jika diperlukan.
6. Buka di browser: `http://localhost/moneytrack/landing`

**Catatan Login:**
Gunakan Private Key rahasia Anda yang valid untuk mengakses sistem.