Bytebook
Projek aplikasi kasir ini merupakan sistem point-of-sale (POS) berbasis web. Aplikasi ini dibangun untuk mengelola transaksi penjualan, data barang, dan pengguna dengan mengombinasikan logika PHP konvensional di sisi server dan interaksi antarmuka menggunakan JavaScript.

🛠️ Teknologi yang Digunakan
Backend: PHP 8 (menggunakan PDO untuk koneksi database).

Database: MySQL / MariaDB.

Frontend: HTML, CSS, dan Vanilla JavaScript ES6.

🗄️ Struktur Database
Skema basis data proyek ini berfokus pada manajemen toko dasar, yang meliputi tabel-tabel berikut:

role: Mengatur hak akses pengguna (Owner, Admin, Kasir).

admin: Menyimpan data akun pengguna aplikasi.

pelanggan: Menyimpan data profil pelanggan toko.

barang: Menyimpan data inventaris, harga beli/jual, dan stok.

transaksi & detail_transaksi: Mencatat histori penjualan toko (total, pembayaran, kembalian, dan jumlah barang yang dibeli).

🔐 Akun Login
Untuk masuk ke dalam aplikasi, Anda dapat menggunakan akun default berikut yang sudah dibagi berdasarkan hak aksesnya (Class):

Class Owner

Username: owner

Password: admin

Class Admin

Username: admin

Password: admin

Class Kasir

Username: kasir

Password: admin

Catatan Keamanan: Password di atas secara default masih di-enkripsi menggunakan MD5 demi menjaga kompatibilitas dengan sistem lama. Jika ingin dikembangkan lebih lanjut untuk tahap produksi, sangat disarankan untuk memperbarui sistem keamanan menggunakan password_hash() bawaan PHP.
