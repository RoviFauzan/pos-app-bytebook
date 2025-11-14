# Kasir App – Supabase (REST via JS) + GitHub

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
- GitHub Pages tidak menjalankan PHP. Untuk publikasi web, gunakan platform yang mendukung PHP atau ubah ke serverless.
- Integrasi umum: Supabase (database) + Vercel (hosting front-end + API). Vercel tidak native mendukung PHP tradisional, tetapi Anda bisa:
  - Opsi A (Disarankan): Refactor endpoint ke JavaScript/TypeScript (Next.js API Routes) dan tetap gunakan Supabase.
  - Opsi B (Eksperimental): Gunakan builder komunitas (vercel-php) atau bungkus via Docker (Vercel Edge limitations).
- Alternatif hosting PHP full: Render, Railway, Hostinger, cPanel, Netlify (dengan adapter), dsb.

## Deploy ke Vercel + Supabase (Mode Sederhana)
Jika ingin cepat online sementara masih memakai PHP, pertimbangkan hosting lain. Tetapi jika tetap memakai Vercel untuk layer front-end (HTML/CSS/JS) dan backend dipisah:

1. Struktur Repo
   - Root repo (GitHub) berisi folder App/.
   - Pastikan file index.html mengarah ke controller/Controller.php (sudah ada).

2. Strategi Deploy
   - Jadikan App sebagai Root Directory di Vercel (Project Settings → General → Root Directory = App).
   - PHP file tidak akan dieksekusi sebagai server-side di Vercel (static delivery only).
   - Untuk fungsi dinamis (login, transaksi) refactor ke:
     - Next.js API Route (pages/api/*.ts) yang memanggil Supabase memakai supabase-js.
     - Atau gunakan Edge Functions Supabase untuk logic yang sensitif.

3. Konversi Minimal (Jika Melanjutkan Refactor)
   - Pindahkan logika autentikasi dari LoginAdmin (PHP) ke endpoint /api/login (JavaScript).
   - Gunakan supabase.auth untuk manajemen user (opsional).
   - Query seperti getDataBarang diganti supabase.from('barang').select('*').

4. Environment Variables di Vercel
   Tambahkan di Project → Settings → Environment Variables:
   - SUPABASE_DB_HOST
   - SUPABASE_DB_PORT
   - SUPABASE_DB_NAME
   - SUPABASE_DB_USER
   - SUPABASE_DB_PASSWORD
   - SUPABASE_DB_SSLMODE (require)
   Jika memakai supabase-js:
   - NEXT_PUBLIC_SUPABASE_URL
   - NEXT_PUBLIC_SUPABASE_ANON_KEY
   - SUPABASE_SERVICE_ROLE_KEY (jangan public)

5. Optional vercel.json (jika mulai refactor ke Next.js)
```json
{
  "version": 2,
  "buildCommand": "npm run build",
  "outputDirectory": ".next",
  "framework": "nextjs",
  "env": {
    "NEXT_PUBLIC_SUPABASE_URL": "@next_public_supabase_url",
    "NEXT_PUBLIC_SUPABASE_ANON_KEY": "@next_public_supabase_anon_key"
  }
}
```

6. Saran Migrasi Bertahap
   - Tahap 1: Push repo ke GitHub (sudah).
   - Tahap 2: Deploy static ke Vercel (hanya UI).
   - Tahap 3: Porting fungsi penting (login, fetch barang, transaksi) ke API serverless.
   - Tahap 4: Matikan PHP dan hapus folder controller/ setelah semua endpoint berpindah.

7. Alternatif Hosting Langsung PHP
   Jika tidak ingin refactor:
   - Render.com: Deploy sebagai Web Service (Dockerfile optional).
   - Railway.app: Import repo, tambah environment variables.
   - Hostinger/cPanel: Upload folder App dan arahkan DocumentRoot.
   - Local (LAN): Gunakan ngrok untuk expose sementara.

## Setup Supabase (Postgres) – Skema Baru
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

## (Opsional) Seeding Minimal
Untuk login awal, buat user seperti di dump lama (password md5('admin') = 21232f297a57a5a743894a0e4a801fc3):

```sql
insert into admin (username, password, nama_admin, id_role) values
('owner', '21232f297a57a5a743894a0e4a801fc3', 'Owner', 1),
('admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 2),
('kasir', '21232f297a57a5a743894a0e4a801fc3', 'Kasir', 3)
on conflict do nothing;
```

## Migrasi Data dari Dump MySQL (database/aplikasi_kasir.sql)
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

## Jalankan Secara Lokal
- Pastikan environment variables sudah diset di terminal yang sama.
- Dari folder App:
  php -S localhost:8000
- Buka http://localhost:8000/index.html (akan redirect ke controller/Controller.php).

## Keamanan
- Jangan commit kredensial Supabase. Simpan sebagai Environment Variables / GitHub Secrets (jika dipakai CI).
- Password admin masih MD5 (kompatibilitas lama). Disarankan migrasi ke password_hash() ke depan.

## Export
- Export Excel tidak butuh Composer. PDF export bisa memakai mPDF via Composer (opsional).

## Troubleshooting cepat
Tambahan khusus Vercel / serverless:
- PHP tidak berjalan → Vercel hanya melayani static (perlu refactor ke JS atau pindah host).
- 404 controller/Controller.php → Pastikan bukan Next.js project; kalau Next.js gunakan routing pages/.
- CORS error saat fetch Supabase → Pastikan URL & anon key benar dan policy RLS sesuai.

Jika memilih refactor penuh ke Next.js + supabase-js, buat checklist baru sebelum menghapus PHP:
- Endpoint login berjalan
- CRUD barang & pelanggan ported
- Transaksi insert + detail berfungsi
- Cetak nota diganti template client-side (React + print)

## Perubahan Terbaru (Migrasi ke Supabase JS)
- Koneksi PHP (controller/Database.php) dinonaktifkan.
- Gunakan file: assets/js/supabase-client.js untuk akses data melalui REST API Supabase.
- Query lama PHP masih berjalan lokal jika Anda aktifkan kembali koneksi, tetapi akan dihapus setelah refactor penuh.

## Supabase JS Client (REST)
Contoh berada di: assets/js/supabase-client.js

Memanggil data:
```html
<script type="module">
  import supabase from '../assets/js/supabase-client.js';
  (async () => {
     const barang = await supabase.fetch('barang?select=*');
     console.log('Data barang:', barang);
  })();
</script>
```

Pastikan table exposed (Row Level Security diatur) atau gunakan service role di server (jangan expose service key ke front-end public).

## Langkah Migrasi Query
1. Identifikasi fungsi PHP di Function.php (getDataBarang, getDataPelanggan, dll).
2. Buat pengganti di JS memakai supabase.fetch('nama_tabel?select=*').
3. Ganti loop PHP di view menjadi render JS (innerHTML).
4. Setelah semua selesai, hapus Function.php dan Controller.php.

## Mode Hybrid
Saat ini aplikasi berjalan dengan:
- Backend PHP (PDO Supabase Postgres) aktif kembali (Database.php)
- Opsional front-end Supabase REST via assets/js/supabase-client.js
Gunakan JS client untuk eksperimen tanpa mematikan fungsi PHP existing.

## Catatan Error “Merah”
Jika sebelumnya muncul error merah pada IDE:
- Penyebab: Database.php mengembalikan null sehingga db()->query() gagal.
- Solusi: Database.php kini memuat koneksi PDO lagi + guard ensureDb() di Function.php.
