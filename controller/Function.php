<?php
// File ini berisi fungsi-fungsi dasar
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Use PDO from Database.php
require_once __DIR__ . "/Database.php";

// ==============================================
//              Kontrol Database
// ==============================================

// Fungsi Login Admin (PDO)
function LoginAdmin($username, $password) {
    $pdo = db();

    $stmt = $pdo->prepare("SELECT a.*, r.nama_role 
                           FROM admin a 
                           JOIN role r ON a.id_role = r.id_role
                           WHERE a.username = :username");
    $stmt->execute([':username' => $username]);
    $data = $stmt->fetch();

    if ($data) {
        $passwordDB = $data['password'];
        $md5Password = md5($password);
        if ($passwordDB === $md5Password) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION['login']      = true;
            $_SESSION['id_admin']   = $data['id_admin'];
            $_SESSION['username']   = $data['username'];
            $_SESSION['nama_admin'] = $data['nama_admin'];
            $_SESSION['id_role']    = $data['id_role'];
            $_SESSION['nama_role']  = $data['nama_role'];
            header("Location: Controller.php?u=home");
            exit;
        } else {
            echo "<script>alert('Password salah!');window.location.href='Controller.php?u=login';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');window.location.href='Controller.php?u=login';</script>";
        exit;
    }
}

// Role-based access control functions
function getUserRole() {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!isset($_SESSION['id_role'])) return 0;
    return $_SESSION['id_role'];
}
function isOwner() { return getUserRole() == 1; }
function isAdmin() { return getUserRole() == 2; }
function isKasir() { return getUserRole() == 3; }
function canAccessAdminSection() { return isOwner(); }
function canAccessTransactionOperations() { return isOwner() || isAdmin() || isKasir(); }
function canViewTransactionData() { return isOwner() || isAdmin(); }

// Fungsi Ubah Akun Admin (PDO)
function ubahAkunAdmin($id_admin, $old_password, $username, $password, $nama_admin){
    $pdo = db();

    $stmt = $pdo->prepare("SELECT password FROM admin WHERE id_admin = :id");
    $stmt->execute([':id' => $id_admin]);
    $result = $stmt->fetch();

    if (!$result) {
        echo "<script>alert('Admin tidak ditemukan!');window.location='$_SERVER[PHP_SELF]?u=home';</script>";
        exit;
    }

    if (md5($old_password) === $result['password']) {
        $hashed = !empty($password) ? md5($password) : $result['password'];
        $up = $pdo->prepare("UPDATE admin SET username = :u, password = :p, nama_admin = :n WHERE id_admin = :id");
        $up->execute([':u'=>$username, ':p'=>$hashed, ':n'=>$nama_admin, ':id'=>$id_admin]);

        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['username']   = $username;
        $_SESSION['nama_admin'] = $nama_admin;
        echo "<script>window.location='$_SERVER[PHP_SELF]?u=logout';</script>";
        exit;
    } else {
        echo "<script>alert('Password lama salah!');window.location='$_SERVER[PHP_SELF]?u=home';</script>";
        exit;
    }
}

// Fungsi Periksa Session Login 
function LoginSessionCheck(){
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!empty($_SESSION['username']) && !empty($_SESSION['nama_admin']) && !empty($_SESSION['key'])){
        echo "<script>alert('Anda sudah login');window.location='$_SERVER[PHP_SELF]?u=home';</script>";
        exit;
    }
}

// Fungsi Periksa Session
function SessionCheck(){
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(empty($_SESSION['username']) && empty($_SESSION['nama_admin']) && empty($_SESSION['key'])){
        echo "<script>alert('Session telah habis. silahkan login kembali.');window.location='$_SERVER[PHP_SELF]?u=login'</script>";
        exit;
    }
}

// Logout
function Logout(){
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    session_destroy();
    echo "<script>alert('Logout berhasil');window.location='$_SERVER[PHP_SELF]?u=login';</script>";
    exit;
}

// =========================
// Admin Functions (PDO)
// =========================
function tambahAdmin($username, $password, $nama_admin, $id_role = 2) {
    $pdo = db();

    $check = $pdo->prepare("SELECT 1 FROM admin WHERE username = :u LIMIT 1");
    $check->execute([':u' => $username]);
    if ($check->fetch()) {
        echo "<script>alert('Username sudah terdaftar!');window.location.href='Controller.php?u=data-admin';</script>";
        return;
    }

    $md5Password = md5($password);
    $stmt = $pdo->prepare("INSERT INTO admin (username, password, nama_admin, id_role) VALUES (:u, :p, :n, :r)");
    if ($stmt->execute([':u'=>$username, ':p'=>$md5Password, ':n'=>$nama_admin, ':r'=>$id_role])) {
        echo "<script>alert('Admin berhasil ditambahkan!');window.location.href='Controller.php?u=data-admin';</script>";
    } else {
        echo "<script>alert('Admin gagal ditambahkan');window.location.href='Controller.php?u=data-admin';</script>";
    }
}

function editAdmin($id_admin, $username, $password, $nama_admin, $id_role) {
    $pdo = db();

    $check = $pdo->prepare("SELECT 1 FROM admin WHERE username = :u AND id_admin != :id LIMIT 1");
    $check->execute([':u' => $username, ':id' => $id_admin]);
    if ($check->fetch()) {
        echo "<script>alert('Username sudah digunakan oleh admin lain!');window.location.href='Controller.php?u=data-admin';</script>";
        return;
    }

    if(!empty($password)) {
        $md5Password = md5($password);
        $stmt = $pdo->prepare("UPDATE admin SET username = :u, password = :p, nama_admin = :n, id_role = :r WHERE id_admin = :id");
        $ok = $stmt->execute([':u'=>$username, ':p'=>$md5Password, ':n'=>$nama_admin, ':r'=>$id_role, ':id'=>$id_admin]);
    } else {
        $stmt = $pdo->prepare("UPDATE admin SET username = :u, nama_admin = :n, id_role = :r WHERE id_admin = :id");
        $ok = $stmt->execute([':u'=>$username, ':n'=>$nama_admin, ':r'=>$id_role, ':id'=>$id_admin]);
    }

    echo "<script>alert('".($ok?'Data admin berhasil diperbarui!':'Data admin gagal diperbarui')."');window.location.href='Controller.php?u=data-admin';</script>";
}

function hapusAdmin($id_admin) {
    $pdo = db();
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    $count = (int)$pdo->query("SELECT COUNT(*) AS total FROM admin")->fetch()['total'];
    if ($count <= 1) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'error',title:'Tidak Dapat Menghapus Admin',text:'Sistem harus memiliki minimal satu admin'}).then(()=>{window.location.href='{$_SERVER['PHP_SELF']}?u=data-admin';});});</script>";
        exit;
    }
    if (!empty($_SESSION['id_admin']) && $_SESSION['id_admin'] == $id_admin) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'error',title:'Tidak Dapat Menghapus',text:'Anda tidak dapat menghapus akun admin yang sedang digunakan'}).then(()=>{window.location.href='{$_SERVER['PHP_SELF']}?u=data-admin';});});</script>";
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM admin WHERE id_admin = :id");
    $stmt->execute([':id'=>$id_admin]);
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'success',title:'Admin Dihapus',text:'Data admin berhasil dihapus dari sistem'}).then(()=>{window.location.href='{$_SERVER['PHP_SELF']}?u=data-admin';});});</script>";
    exit;
}

function getDataAdmin() {
    $pdo = db();
    $stmt = $pdo->query("SELECT a.*, r.nama_role FROM admin a JOIN role r ON a.id_role = r.id_role ORDER BY a.id_admin");
    return $stmt->fetchAll();
}

// =========================
// Barang Functions (PDO)
// =========================
function tambahBarang($nama_barang, $merk, $harga_beli, $harga_jual, $stok){
    $pdo = db();
    $stmt = $pdo->prepare("INSERT INTO barang (nama_barang, merk, harga_beli, harga_jual, stok) VALUES (:n,:m,:hb,:hj,:s)");
    $stmt->execute([':n'=>$nama_barang, ':m'=>$merk, ':hb'=>$harga_beli, ':hj'=>$harga_jual, ':s'=>$stok]);
    echo "<script>window.location='$_SERVER[PHP_SELF]?u=data-barang';</script>";
    exit;
}

function getDataBarang(){
    $pdo = db();
    $stmt = $pdo->query("SELECT * FROM barang");
    return $stmt->fetchAll();
}

function editBarang($conn_unused, $id_barang, $nama_barang, $harga_beli, $harga_jual, $stok, $merk) {
    $pdo = db();
    $stmt = $pdo->prepare("UPDATE barang SET nama_barang=:n, harga_beli=:hb, harga_jual=:hj, stok=:s, merk=:m WHERE id_barang=:id");
    $ok = $stmt->execute([':n'=>$nama_barang, ':hb'=>$harga_beli, ':hj'=>$harga_jual, ':s'=>$stok, ':m'=>$merk, ':id'=>$id_barang]);
    echo "<script>alert('".($ok?'Data barang berhasil diupdate':'Gagal mengupdate data barang')."');window.location='$_SERVER[PHP_SELF]?u=data-barang';</script>";
    exit;
}

function hapusBarang($id_barang){
    $pdo = db();
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id_barang=:id");
    $stmt->execute([':id'=>$id_barang]);
    echo "<script>window.location='$_SERVER[PHP_SELF]?u=data-barang';</script>";
    exit;
}

function countRowsBarang(){
    $pdo = db();
    return (int)$pdo->query("SELECT COUNT(*) AS total_rows FROM barang")->fetch()['total_rows'];
}

// =========================
// Pelanggan Functions (PDO)
// =========================
function tambahPelanggan($nama_pelanggan, $no_hp, $alamat, $email){
    $pdo = db();
    $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat, email) VALUES (:n,:hp,:al,:em)");
    $stmt->execute([':n'=>$nama_pelanggan, ':hp'=>$no_hp, ':al'=>$alamat, ':em'=>$email]);
    echo "<script>window.location='$_SERVER[PHP_SELF]?u=data-pelanggan';</script>";
    exit;
}

function getDataPelanggan(){
    $pdo = db();
    $stmt = $pdo->query("SELECT * FROM pelanggan");
    return $stmt->fetchAll();
}

function editPelanggan($conn_unused, $id_pelanggan, $nama_pelanggan, $no_hp, $alamat, $email){
    $pdo = db();
    $stmt = $pdo->prepare("UPDATE pelanggan SET nama_pelanggan=:n, no_hp=:hp, alamat=:al, email=:em WHERE id_pelanggan=:id");
    $ok = $stmt->execute([':n'=>$nama_pelanggan, ':hp'=>$no_hp, ':al'=>$alamat, ':em'=>$email, ':id'=>$id_pelanggan]);
    echo "<script>alert('".($ok?'Data pelanggan berhasil diupdate':'Gagal mengupdate data pelanggan')."');window.location='$_SERVER[PHP_SELF]?u=data-pelanggan';</script>";
    exit;
}

function hapusPelanggan($id_pelanggan){
    $pdo = db();
    $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan=:id");
    $stmt->execute([':id'=>$id_pelanggan]);
    echo "<script>window.location='$_SERVER[PHP_SELF]?u=data-pelanggan';</script>";
    exit;
}

function countRowsPelanggan(){
    $pdo = db();
    return (int)$pdo->query("SELECT COUNT(*) AS total_rows FROM pelanggan")->fetch()['total_rows'];
}

// =========================
// Transaksi (PDO)
// =========================
function getDataTransaksi(){
    $pdo = db();
    $stmt = $pdo->query("SELECT * FROM transaksi");
    return $stmt->fetchAll();
}

function editTransaksi($id_transaksi, $tanggal, $total_pembelian, $kembalian, $bayar, $keterangan){
    $pdo = db();
    $stmt = $pdo->prepare("UPDATE transaksi 
                           SET tanggal=:t, total_pembelian=:tp, kembalian=:k, bayar=:b, keterangan=:ket 
                           WHERE id_transaksi=:id");
    $stmt->execute([':t'=>$tanggal, ':tp'=>$total_pembelian, ':k'=>$kembalian, ':b'=>$bayar, ':ket'=>$keterangan, ':id'=>$id_transaksi]);
    echo "<script>window.location='$_SERVER[PHP_SELF]?u=data-transaksi';</script>";
    exit;
}

function hapusTransaksi($id_transaksi){
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $d = $pdo->prepare("DELETE FROM detail_transaksi WHERE id_transaksi=:id");
        $d->execute([':id'=>$id_transaksi]);
        $t = $pdo->prepare("DELETE FROM transaksi WHERE id_transaksi=:id");
        $t->execute([':id'=>$id_transaksi]);
        $pdo->commit();
        echo "<script>window.location='$_SERVER[PHP_SELF]?u=data-transaksi';</script>";
        exit;
    } catch (Throwable $e) {
        $pdo->rollBack();
        die("Query error: " . $e->getMessage());
    }
}

function hitungOmsetPenjualan(){
    $pdo = db();
    return (float)($pdo->query("SELECT COALESCE(SUM(total_pembelian),0) AS omset FROM transaksi")->fetch()['omset'] ?? 0);
}

function hitungPendapatanBersih(){
    $pdo = db();
    $sqlJual = "SELECT COALESCE(SUM(b.harga_jual * dt.qty),0) AS total_harga_jual
                FROM detail_transaksi dt INNER JOIN barang b ON dt.id_barang = b.id_barang";
    $sqlBeli = "SELECT COALESCE(SUM(b.harga_beli * dt.qty),0) AS total_harga_beli
                FROM detail_transaksi dt INNER JOIN barang b ON dt.id_barang = b.id_barang";
    $totalHargaJual = (float)$pdo->query($sqlJual)->fetch()['total_harga_jual'];
    $totalHargaBeli = (float)$pdo->query($sqlBeli)->fetch()['total_harga_beli'];
    return $totalHargaJual - $totalHargaBeli;
}

function getDetailTransaksiByTransaksiId($id_transaksi){
    $pdo = db();
    $stmt = $pdo->prepare("SELECT dt.id_detail_transaksi, dt.id_barang, b.nama_barang, dt.qty, b.harga_jual, (dt.qty * b.harga_jual) AS total
                           FROM detail_transaksi dt 
                           INNER JOIN barang b ON dt.id_barang = b.id_barang
                           WHERE dt.id_transaksi = :id");
    $stmt->execute([':id'=>$id_transaksi]);
    return $stmt->fetchAll();
}

// fungsi cetak nota
function cetakNota($id_transaksi) {
    $pdo = db();
    try {
        $id_transaksi = max(1, intval($id_transaksi));
        $t = $pdo->prepare("SELECT t.*, p.nama_pelanggan, p.alamat, p.no_hp 
                            FROM transaksi t 
                            JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
                            WHERE t.id_transaksi = :id");
        $t->execute([':id'=>$id_transaksi]);
        if ($t->rowCount() === 0) throw new Exception("Transaksi dengan ID $id_transaksi tidak ditemukan");
        $transaksi = $t->fetch();
        $transaksi['id_transaksi'] = max(1, intval($transaksi['id_transaksi']));

        $d = $pdo->prepare("SELECT dt.*, b.nama_barang, b.harga_jual 
                            FROM detail_transaksi dt 
                            JOIN barang b ON dt.id_barang = b.id_barang 
                            WHERE dt.id_transaksi = :id");
        $d->execute([':id'=>$id_transaksi]);
        $detailTransaksi = $d->fetchAll();
        if (empty($detailTransaksi)) throw new Exception("Detail transaksi kosong untuk transaksi ID $id_transaksi");

        include "../view/cetaknota.php";
    } catch (Exception $e) {
        error_log("Error in cetakNota: " . $e->getMessage());
        echo "<div class='alert alert-danger'>Terjadi kesalahan saat mencetak nota: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

function getRecentTransactions($limit = 3) {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT t.*, p.nama_pelanggan 
                           FROM transaksi t
                           INNER JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
                           ORDER BY t.tanggal DESC 
                           LIMIT :lim");
    $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getMonthlySalesData($year = null) {
    $pdo = db();
    if ($year === null) $year = date('Y');
    $salesData = array_fill(0, 12, 0.0);

    $sql = "SELECT EXTRACT(MONTH FROM tanggal)::int AS bulan, SUM(total_pembelian)::numeric AS total
            FROM transaksi
            WHERE EXTRACT(YEAR FROM tanggal) = :y
            GROUP BY EXTRACT(MONTH FROM tanggal)
            ORDER BY EXTRACT(MONTH FROM tanggal)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':y' => (int)$year]);

    while ($row = $stmt->fetch()) {
        $monthIndex = max(0, (int)$row['bulan'] - 1);
        $salesData[$monthIndex] = (float)$row['total'];
    }
    return $salesData;
}

function countTransactions() {
    $pdo = db();
    return (int)$pdo->query("SELECT COUNT(*) AS total FROM transaksi")->fetch()['total'];
}

function getCategoryData() {
    $pdo = db();
    $stmt = $pdo->query("SELECT merk, COUNT(*) as jumlah FROM barang GROUP BY merk ORDER BY jumlah DESC");
    $categories = [];
    $counts = [];
    foreach ($stmt->fetchAll() as $row) {
        $categories[] = $row['merk'];
        $counts[] = (int)$row['jumlah'];
    }
    return ['categories' => $categories, 'counts' => $counts];
}

function getMonthlyChartData() {
    $pdo = db();
    $year = date('Y');
    $salesData = array_fill(1, 12, 0.0);

    $sql = "SELECT EXTRACT(MONTH FROM tanggal)::int AS month, SUM(total_pembelian)::numeric AS total
            FROM transaksi 
            WHERE EXTRACT(YEAR FROM tanggal) = :y
            GROUP BY EXTRACT(MONTH FROM tanggal)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':y'=>$year]);
    while($row = $stmt->fetch()) {
        $salesData[(int)$row['month']] = (float)$row['total'];
    }
    return array_values($salesData);
}

function ubahNamaAdmin($id_admin, $nama_admin) {
    $pdo = db();
    $stmt = $pdo->prepare("UPDATE admin SET nama_admin=:n WHERE id_admin=:id");
    $stmt->execute([':n'=>$nama_admin, ':id'=>$id_admin]);

    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['nama_admin'] = $nama_admin;

    echo "<script>alert('Nama admin berhasil diperbarui.'); window.location='dashboard.php';</script>";
    exit;
}

// Get Product Distribution Data for Chart
function getProductDistributionData($viewType = 'count') {
    $pdo = db();

    $result = [
        'labels' => [],
        'data' => []
    ];

    if ($viewType === 'count') {
        $sql = "SELECT merk, COUNT(*) AS total 
                FROM barang 
                GROUP BY merk 
                ORDER BY total DESC 
                LIMIT 7";
    } else {
        $sql = "SELECT merk, COALESCE(SUM(stok),0) AS total 
                FROM barang 
                GROUP BY merk 
                ORDER BY total DESC 
                LIMIT 7";
    }

    $stmt = $pdo->query($sql);
    foreach ($stmt->fetchAll() as $row) {
        $result['labels'][] = $row['merk'];
        $result['data'][] = (int)$row['total'];
    }

    return $result;
}
?>