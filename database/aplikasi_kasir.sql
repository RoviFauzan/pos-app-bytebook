-- Postgres-compatible SQL for Kasir App (Supabase)
-- This replaces the MySQL/MariaDB dump

BEGIN;

-- Drop existing tables (order matters due to FKs)
DROP TABLE IF EXISTS detail_transaksi CASCADE;
DROP TABLE IF EXISTS transaksi CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS barang CASCADE;
DROP TABLE IF EXISTS pelanggan CASCADE;
DROP TABLE IF EXISTS role CASCADE;

-- Table: role
CREATE TABLE role (
  id_role SERIAL PRIMARY KEY,
  nama_role VARCHAR(50) NOT NULL,
  deskripsi TEXT
);

-- Seed roles
INSERT INTO role (id_role, nama_role, deskripsi) VALUES
  (1, 'Owner', 'Pemilik usaha dengan akses penuh ke seluruh sistem'),
  (2, 'Admin', 'Administrator dengan akses ke manajemen sistem'),
  (3, 'Kasir', 'Petugas kasir dengan akses terbatas untuk transaksi')
ON CONFLICT (id_role) DO NOTHING;

-- Table: admin
CREATE TABLE admin (
  id_admin SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_admin VARCHAR(100) NOT NULL,
  id_role INT NOT NULL REFERENCES role(id_role)
);

-- Data: admin
INSERT INTO admin (id_admin, username, password, nama_admin, id_role) VALUES
  (1, 'owner', '21232f297a57a5a743894a0e4a801fc3', 'Owner', 1),
  (2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 2),
  (3, 'kasir', '21232f297a57a5a743894a0e4a801fc3', 'Kasir', 3)
ON CONFLICT (id_admin) DO NOTHING;

-- Table: barang
CREATE TABLE barang (
  id_barang SERIAL PRIMARY KEY,
  harga_beli NUMERIC(10,2) NOT NULL,
  harga_jual NUMERIC(10,2) NOT NULL,
  stok INT NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  merk VARCHAR(50) NOT NULL
);

-- Data: barang
INSERT INTO barang (id_barang, harga_beli, harga_jual, stok, nama_barang, merk) VALUES
  (1, 1500000.00, 2000000.00, 9, 'Laptop Asus', 'Merk A'),
  (2, 2000000.00, 2500000.00, 2, 'Laptop Toshiba F', 'Merk B'),
  (3, 1800000.00, 2300000.00, 7, 'Laptop Dell', 'Merk C'),
  (12, 5000000.00, 5500000.00, 4, 'Macbook Air', 'Apple')
ON CONFLICT (id_barang) DO NOTHING;

-- Table: pelanggan
CREATE TABLE pelanggan (
  id_pelanggan SERIAL PRIMARY KEY,
  nama_pelanggan VARCHAR(100) NOT NULL,
  no_hp VARCHAR(15) NOT NULL,
  alamat TEXT NOT NULL,
  email VARCHAR(100) NOT NULL
);

-- Data: pelanggan
-- Add a placeholder pelanggan with id 0 to satisfy legacy transaksi rows where id_pelanggan=0
INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, no_hp, alamat, email) VALUES
  (0, 'Umum', '-', '-', 'umum@example.com')
ON CONFLICT (id_pelanggan) DO NOTHING;

INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, no_hp, alamat, email) VALUES
  (1, 'Budi Santoso', '081234567890', 'Jl. Merdeka No. 1, Jakarta', 'budi@example.com'),
  (2, 'Ani Yulianti', '081234567891', 'Jl. Sudirman No. 2, Jakarta 2', 'aniyuli@gmail.com'),
  (6, 'Renasya', '089347009002', 'Jl Batara', 'renasyaa@gmail.com'),
  (7, 'Desti', '082106892022', 'Jl Peta', 'destii@gmail.com')
ON CONFLICT (id_pelanggan) DO NOTHING;

-- Table: transaksi
CREATE TABLE transaksi (
  id_transaksi SERIAL PRIMARY KEY,
  id_pelanggan INT NOT NULL REFERENCES pelanggan(id_pelanggan),
  tanggal TIMESTAMP NOT NULL,
  total_pembelian NUMERIC(10,0) NOT NULL,
  kembalian NUMERIC(10,0) NOT NULL,
  bayar NUMERIC(10,0) NOT NULL,
  keterangan TEXT
);

-- Data: transaksi
INSERT INTO transaksi (id_transaksi, id_pelanggan, tanggal, total_pembelian, kembalian, bayar, keterangan) VALUES
  (1, 0, '2024-05-15 14:30:00', 3500000, 500000, 7000000, 'Pembelian Laptop untuk kantor'),
  (2, 0, '2024-05-15 15:00:00', 2300000, 200000, 2500000, 'Pembelian Laptop pribadi'),
  (26, 2, '2024-06-02 13:42:00', 4300000, 700000, 5000000, 'asfasfasfasfasf13'),
  (27, 2, '2024-06-02 18:43:00', 6600000, 3400000, 10000000, 'asfasgafs g a'),
  (28, 1, '2024-06-02 18:52:00', 2000000, 3000000, 5000000, 'ds'),
  (30, 2, '2024-06-02 21:28:00', 6000000, 0, 6000000, 'buat kuliah'),
  (31, 2, '2024-06-03 07:13:00', 6600000, 3400000, 10000000, 'Untuk keperluan kuliah'),
  (32, 2, '2024-06-03 12:43:00', 2500000, 0, 2500000, 'untuk kantor'),
  (33, 1, '2024-06-03 18:05:00', 4000000, 1000000, 5000000, 'untuk kantp')
ON CONFLICT (id_transaksi) DO NOTHING;

-- Index to help queries
CREATE INDEX IF NOT EXISTS idx_transaksi_id_pelanggan ON transaksi(id_pelanggan);

-- Table: detail_transaksi
CREATE TABLE detail_transaksi (
  id_detail_transaksi SERIAL PRIMARY KEY,
  id_transaksi INT NOT NULL REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
  id_barang INT NOT NULL REFERENCES barang(id_barang),
  qty INT NOT NULL
);

-- Data: detail_transaksi
INSERT INTO detail_transaksi (id_detail_transaksi, id_transaksi, id_barang, qty) VALUES
  (1, 1, 1, 2),
  (2, 1, 2, 1),
  (19, 26, 1, 1),
  (20, 26, 3, 1),
  (21, 27, 1, 1),
  (22, 27, 3, 2),
  (23, 28, 1, 1),
  (25, 30, 1, 1),
  (26, 30, 1, 2),
  (27, 31, 1, 1),
  (28, 31, 3, 2),
  (29, 32, 2, 1),
  (30, 33, 1, 2)
ON CONFLICT (id_detail_transaksi) DO NOTHING;

-- Helpful indexes
CREATE INDEX IF NOT EXISTS idx_detail_transaksi_transaksi ON detail_transaksi(id_transaksi);
CREATE INDEX IF NOT EXISTS idx_detail_transaksi_barang ON detail_transaksi(id_barang);

-- Reset sequences to match current max IDs
SELECT setval(pg_get_serial_sequence('role', 'id_role'),          COALESCE((SELECT MAX(id_role) FROM role), 1), true);
SELECT setval(pg_get_serial_sequence('admin', 'id_admin'),        COALESCE((SELECT MAX(id_admin) FROM admin), 1), true);
SELECT setval(pg_get_serial_sequence('barang', 'id_barang'),      COALESCE((SELECT MAX(id_barang) FROM barang), 1), true);
SELECT setval(pg_get_serial_sequence('pelanggan', 'id_pelanggan'),COALESCE((SELECT MAX(id_pelanggan) FROM pelanggan), 1), true);
SELECT setval(pg_get_serial_sequence('transaksi', 'id_transaksi'),COALESCE((SELECT MAX(id_transaksi) FROM transaksi), 1), true);
SELECT setval(pg_get_serial_sequence('detail_transaksi', 'id_detail_transaksi'),
              COALESCE((SELECT MAX(id_detail_transaksi) FROM detail_transaksi), 1), true);

COMMIT;
