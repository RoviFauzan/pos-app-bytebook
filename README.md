# Kasir App – Supabase + GitHub Quick Start

Projek ini menggunakan PHP 8 + Supabase (Postgres via PDO). Dokumen ini membantu menyiapkan repo GitHub, environment Supabase, migrasi dari dump MySQL yang ada, serta cara menjalankan lokal.

Ringkas perubahan (dari versi XAMPP/MySQL)
- Migrasi ke Postgres (Supabase) via PDO.
- Koneksi di controller/Database.php membaca Environment Variables.
- Query MySQL khusus (MONTH, YEAR) diganti ke EXTRACT Postgres.

Struktur database legacy (MySQL)
- Tersedia dump MySQL lama: database/aplikasi_kasir.sql (MariaDB/MySQL).
- Aplikasi sekarang memakai Postgres di Supabase. Anda bisa:
  - Opsi A (Direkomendasikan): Pakai skema Postgres baru (di bawah) + seeding minimal.
  - Opsi B: Migrasikan data dari dump MySQL ke Supabase (lihat panduan di bawah).

1) Siapkan Repository GitHub
- Buat repo baru di GitHub (public/private).
- Jalankan di terminal pada folder d:\pos-app-bytebook\App (atau root project Anda):

```bash
git init
git add .
git commit -m "init: kasir app (php + supabase)"
git branch -M main
git remote add origin https://github.com/<username>/<repo>.git
git push -u origin main
```

Catatan:
- GitHub Pages tidak menjalankan PHP. Repo ini untuk source control. Untuk menjalankan aplikasinya, gunakan server PHP (local/hosting/VPS).

2) Rekomendasi .gitignore (jangan commit rahasia)
Tambahkan file .gitignore di root project Anda:

```gitignore
# OS / editor
.DS_Store
Thumbs.db
*.log
.vscode/
.idea/

# Env & secrets
.env
*.env

# Composer / vendor (jika nanti menambah library)
vendor/

# Node (jika menambah tooling front-end)
node_modules/
```

3) Environment Variables (dibaca oleh Database.php)
Aplikasi membaca variabel dari environment:
- SUPABASE_DB_HOST=xxxx.supabase.co
- SUPABASE_DB_PORT=5432
- SUPABASE_DB_NAME=postgres
- SUPABASE_DB_USER=postgres
- SUPABASE_DB_PASSWORD=your-db-password
- SUPABASE_DB_SSLMODE=require

Cara set di lokal:
- PowerShell (Windows):
  $env:SUPABASE_DB_HOST="xxxx.supabase.co"
  (set variabel lain serupa, lalu jalankan server PHP di session yang sama)
- Web server (Apache/Nginx/PHP-FPM): set via konfigurasi server/hosting (disarankan untuk production).

4) Setup Supabase (Postgres) – Skema Baru
- Buat project di https://supabase.com
- Ambil kredensial DB dari Settings → Database.
- Jalankan SQL skema berikut di SQL Editor Supabase:

```sql
-- Roles
create table if not exists role (
  id_role serial primary key,
  nama_role text not null
);

-- Admin
create table if not exists admin (
  id_admin serial primary key,
  username text not null unique,
  password text not null,
  nama_admin text not null,
  id_role int not null references role(id_role)
);

-- Pelanggan
create table if not exists pelanggan (
  id_pelanggan serial primary key,
  nama_pelanggan text not null,
  no_hp text,
  alamat text,
  email text
);

-- Barang
create table if not exists barang (
  id_barang serial primary key,
  nama_barang text not null,
  merk text,
  harga_beli numeric(18,2) not null default 0,
  harga_jual numeric(18,2) not null default 0,
  stok int not null default 0
);

-- Transaksi
create table if not exists transaksi (
  id_transaksi serial primary key,
  tanggal timestamp without time zone not null default now(),
  id_pelanggan int not null references pelanggan(id_pelanggan),
  total_pembelian numeric(18,2) not null default 0,
  bayar numeric(18,2) not null default 0,
  kembalian numeric(18,2) not null default 0,
  keterangan text
);

-- Detail Transaksi
create table if not exists detail_transaksi (
  id_detail_transaksi serial primary key,
  id_transaksi int not null references transaksi(id_transaksi) on delete cascade,
  id_barang int not null references barang(id_barang),
  qty int not null default 0
);

-- Seed default roles
insert into role (nama_role) values ('Owner'), ('Admin'), ('Kasir')
on conflict do nothing;
```

5) (Opsional) Seeding Minimal
Untuk login awal, buat user seperti di dump lama (password md5('admin') = 21232f297a57a5a743894a0e4a801fc3):

```sql
insert into admin (username, password, nama_admin, id_role) values
('owner', '21232f297a57a5a743894a0e4a801fc3', 'Owner', 1),
('admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 2),
('kasir', '21232f297a57a5a743894a0e4a801fc3', 'Kasir', 3)
on conflict do nothing;
```

6) Migrasi Data dari Dump MySQL (database/aplikasi_kasir.sql)
Anda punya dua opsi alat:

- Opsi 1: DBeaver (GUI – mudah)
  1. Import database/aplikasi_kasir.sql ke MySQL lokal (XAMPP/MariaDB).
  2. Buka DBeaver, buat koneksi ke MySQL (source) dan ke Supabase Postgres (target).
  3. Klik kanan schema MySQL → Tools → Data Transfer.
  4. Pilih target Postgres, mapping tabel/kolom otomatis (cek tipe: datetime → timestamp, decimal → numeric).
  5. Jalankan transfer.

- Opsi 2: pgloader (CLI – cepat)
  1. Import database/aplikasi_kasir.sql ke MySQL lokal (buat DB ‘aplikasi_kasir’, lalu import via phpMyAdmin).
  2. Install pgloader (https://pgloader.readthedocs.io).
  3. Jalankan:

```bash
pgloader mysql://root:password@127.0.0.1/aplikasi_kasir \
  postgresql://postgres:SUPABASE_DB_PASSWORD@SUPABASE_DB_HOST:5432/SUPABASE_DB_NAME
```

Catatan migrasi:
- Hapus/abaikan baris MySQL spesifik seperti SET SQL_MODE, ENGINE, COLLATE dalam dump.
- AUTO_INCREMENT (MySQL) setara SERIAL di Postgres (skema baru sudah memakai SERIAL).
- datetime → timestamp, decimal → numeric (skala/precision bisa diatur).
- Pastikan FK detail_transaksi.id_transaksi punya ON DELETE CASCADE (sudah ada di skema).
- Setelah migrasi, verifikasi jumlah baris per tabel dan coba login.

7) Jalankan Secara Lokal
- Pastikan environment variables sudah diset di terminal yang sama.
- Dari folder App:
  php -S localhost:8000
- Buka http://localhost:8000/index.html (akan redirect ke controller/Controller.php).

8) Keamanan
- Jangan commit kredensial Supabase. Simpan sebagai Environment Variables / GitHub Secrets (jika dipakai CI).
- Password admin masih MD5 (kompatibilitas lama). Disarankan migrasi ke password_hash() ke depan.

9) Export
- Export Excel tidak butuh Composer. PDF export bisa memakai mPDF via Composer (opsional).

Troubleshooting cepat
- Error koneksi DB: cek ENV sudah diset dan nilai host/port/ssl benar.
- Migrasi gagal: cek mapping tipe data (datetime→timestamp, decimal→numeric).
- Blank page/error PHP: pakai PHP 8+ dan cek error log di server/terminal.
