# 🛒 Bytebook - Point of Sale (POS) Web App

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)

Bytebook adalah sistem *Point-of-Sale* (POS) berbasis web yang dirancang khusus untuk mengelola transaksi penjualan, manajemen inventaris barang, dan data pengguna toko secara efisien dengan menggunakan logika PHP.

---

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP 8 (menggunakan PDO untuk koneksi database yang lebih aman)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, dan Vanilla JavaScript ES6

---

## 🗄️ Struktur Database

Skema basis data proyek ini berfokus pada manajemen toko dasar. Berikut adalah tabel-tabel utama yang digunakan:

| Nama Tabel | Deskripsi |
| --- | --- |
| `role` | Mengatur hak akses pengguna (Owner, Admin, Kasir). |
| `admin` | Menyimpan data akun pengguna aplikasi. |
| `pelanggan` | Menyimpan data profil pelanggan toko. |
| `barang` | Menyimpan data inventaris, harga beli/jual, dan stok barang. |
| `transaksi` & `detail_transaksi` | Mencatat histori penjualan toko (total tagihan, jumlah pembayaran, kembalian, dan jumlah barang yang dibeli). |

---

## 🔐 Akun Login (Default)

Untuk masuk ke dalam aplikasi dan menguji coba fitur, Anda dapat menggunakan salah satu akun default di bawah ini sesuai dengan hak akses *(Role)* masing-masing:

| Hak Akses (Role) | Username | Password |
| :---: | :---: | :---: |
| 👑 **Owner** | `owner` | `admin` |
| 🛡️ **Admin** | `admin` | `admin` |
| 🧑‍💻 **Kasir** | `kasir` | `admin` |

> ⚠️ **Catatan Keamanan:** 
> Password di atas secara default masih dienkripsi menggunakan **MD5** demi menjaga kompatibilitas dengan sistem lama. Jika aplikasi ini ingin dikembangkan lebih lanjut untuk tahap produksi (Production), sangat disarankan untuk mengubah metode enkripsi ke algoritma yang lebih modern dan aman seperti **Bcrypt** atau **Argon2**.

---

<p align="center">
  Dibuat dengan ❤️ oleh RoviFauzan
</p>
