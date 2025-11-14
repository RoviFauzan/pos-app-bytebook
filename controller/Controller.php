<?php 
include "Function.php";
date_default_timezone_set('Asia/Jakarta');

// POST Method
if(isset($_POST['login-admin'])){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    LoginAdmin($username, $password);
} 
else if(isset($_POST['tambah-admin'])){
    $username   = $_POST['username'] ?? '';
    $password   = $_POST['password'] ?? '';
    $nama_admin = $_POST['nama_admin'] ?? '';
    $id_role    = (int)($_POST['id_role'] ?? 2);
    tambahAdmin($username, $password, $nama_admin, $id_role);
}
else if(isset($_POST['ubah-akun-admin'])){
    $id_admin    = (int)($_POST['id_admin'] ?? 0);
    $old_password= $_POST['old_password'] ?? '';
    $username    = $_POST['username'] ?? '';
    $password    = $_POST['password'] ?? '';
    $nama_admin  = $_POST['nama_admin'] ?? '';
    ubahAkunAdmin($id_admin, $old_password, $username, $password, $nama_admin);
} 
else if(isset($_POST['tambah-data-pelanggan'])){
    session_start();
    if(isOwner()) {
        echo "<script>alert('Maaf, akun dengan role Owner tidak diizinkan untuk menambahkan pelanggan');window.location.href='{$_SERVER['PHP_SELF']}?u=data-pelanggan';</script>";
        exit;
    }
    $nama_pelanggan = $_POST['nama_pelanggan'] ?? '';
    $no_hp          = $_POST['no_hp'] ?? '';
    $alamat         = $_POST['alamat'] ?? '';
    $email          = $_POST['email'] ?? '';
    tambahPelanggan($nama_pelanggan, $no_hp, $alamat, $email);
} 
else if(isset($_POST['edit-pelanggan'])){
    $id_pelanggan   = (int)($_POST['id_pelanggan'] ?? 0);
    $nama_pelanggan = $_POST['nama_pelanggan'] ?? '';
    $no_hp          = $_POST['no_hp'] ?? '';
    $alamat         = $_POST['alamat'] ?? '';
    $email          = $_POST['email'] ?? '';
    editPelanggan(null, $id_pelanggan, $nama_pelanggan, $no_hp, $alamat, $email);
} 
else if(isset($_POST['tambah-data-barang'])){
    $harga_beli  = $_POST['harga_beli'] ?? '0';
    $harga_jual  = $_POST['harga_jual'] ?? '0';
    $stok        = $_POST['stok'] ?? '0';
    $nama_barang = $_POST['nama_barang'] ?? '';
    $merk        = $_POST['merk'] ?? '';
    tambahBarang($nama_barang, $merk, $harga_beli, $harga_jual, $stok);
} 
else if(isset($_POST['edit-barang'])){
    $id_barang   = (int)($_POST['id_barang'] ?? 0);
    $nama_barang = $_POST['nama_barang'] ?? '';
    $merk        = $_POST['merk'] ?? '';
    $harga_beli  = $_POST['harga_beli'] ?? '0';
    $harga_jual  = $_POST['harga_jual'] ?? '0';
    $stok        = $_POST['stok'] ?? '0';
    editBarang(null, $id_barang, $nama_barang, $harga_beli, $harga_jual, $stok, $merk);
} 
else if(isset($_POST['tambah-transaksi'])) {
    // Re-implement using PDO transaction
    $pdo = db();
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    try {
        $tanggal         = $_POST['tanggal_transaksi'] ?? date('Y-m-d H:i:s');
        $id_pelanggan    = (int)($_POST['id_pelanggan'] ?? 0);
        $total_pembelian = (float)($_POST['total_pembelian'] ?? 0);
        $bayar           = (float)($_POST['bayar'] ?? 0);
        $kembalian       = $bayar - $total_pembelian;
        $keterangan      = $_POST['keterangan'] ?? '';

        $detail_transaksi = $_POST['detail_transaksi'] ?? '[]';
        if (is_array($detail_transaksi)) $detail_transaksi = $detail_transaksi[0] ?? '[]';
        $items = json_decode($detail_transaksi, true);
        if (!is_array($items) || empty($items)) {
            throw new Exception("Invalid transaction details format");
        }

        $pdo->beginTransaction();

        // Insert transaksi and get id using RETURNING
        $stmt = $pdo->prepare("INSERT INTO transaksi (tanggal, id_pelanggan, total_pembelian, bayar, kembalian, keterangan)
                               VALUES (:t,:p,:tp,:b,:k,:ket)
                               RETURNING id_transaksi");
        $stmt->execute([
            ':t'=>$tanggal, ':p'=>$id_pelanggan, ':tp'=>$total_pembelian,
            ':b'=>$bayar, ':k'=>$kembalian, ':ket'=>$keterangan
        ]);
        $id_transaksi = (int)$stmt->fetchColumn();
        if ($id_transaksi <= 0) throw new Exception("Gagal mendapatkan ID transaksi");

        // Process details
        $sel = $pdo->prepare("SELECT stok FROM barang WHERE id_barang = :id");
        $ins = $pdo->prepare("INSERT INTO detail_transaksi (id_transaksi, id_barang, qty) VALUES (:t,:b,:q)");
        $upd = $pdo->prepare("UPDATE barang SET stok = stok - :q WHERE id_barang = :id");

        foreach ($items as $detail) {
            $id_barang = (int)$detail['id_barang'];
            $qty       = (int)$detail['qty'];

            $sel->execute([':id'=>$id_barang]);
            $row = $sel->fetch();
            if (!$row) throw new Exception("Product with ID $id_barang not found");
            $current_stock = (int)$row['stok'];
            if ($current_stock < $qty) throw new Exception("Insufficient stock for product ID $id_barang");

            $ins->execute([':t'=>$id_transaksi, ':b'=>$id_barang, ':q'=>$qty]);
            $upd->execute([':q'=>$qty, ':id'=>$id_barang]);
        }

        $pdo->commit();
        header("Location: Controller.php?u=print-nota&id={$id_transaksi}");
        exit;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log("Transaction error: " . $e->getMessage());
        echo "<div class='alert alert-danger'><h4>Transaction Failed</h4><p>" . htmlspecialchars($e->getMessage()) . "</p></div>
              <div class='text-center'><a href='Controller.php?u=transaksi' class='btn btn-primary'>Return to Transaction Form</a></div>";
        exit;
    }
} 
else if(isset($_POST['hapus-pelanggan'])){
    $id_pelanggan = (int)($_POST['id_pelanggan'] ?? 0);
    hapusPelanggan($id_pelanggan);
} 
else if(isset($_POST['hapus-barang'])){
    $id_barang = (int)($_POST['id_barang'] ?? 0);
    hapusBarang($id_barang);
} 
else if(isset($_POST['hapus-transaksi'])){
    $id_transaksi = (int)($_POST['id_transaksi'] ?? 0);
    hapusTransaksi($id_transaksi);
} 
else if(isset($_POST['hapus-detail-transaksi'])){
    $id_detail_transaksi = (int)($_POST['id_detail_transaksi'] ?? 0);
    // ...existing code for delete detail if you have a function...
} 
else if (isset($_POST['ubah-nama-admin'])) {
    $id_admin   = (int)($_POST['id_admin'] ?? 0);
    $nama_admin = $_POST['nama_admin'] ?? '';
    ubahNamaAdmin($id_admin, $nama_admin);
}
else if(isset($_POST['edit-admin'])){
    $id_admin  = (int)($_POST['id_admin'] ?? 0);
    $username  = $_POST['username'] ?? '';
    $password  = $_POST['password'] ?? '';
    $nama_admin= $_POST['nama_admin'] ?? '';
    $id_role   = (int)($_POST['id_role'] ?? 2);
    editAdmin($id_admin, $username, $password, $nama_admin, $id_role);
}

// GET Method
if(isset($_GET['u'])){
    $url = $_GET["u"];
    if($url == "login"){
        LoginSessionCheck();
        include "../view/login.php";
    } else if($url == "logout"){
        Logout();
    } else if($url == "home"){
        SessionCheck();
        include "../view/dashboard.php";
    } else if($url == "data-pelanggan"){
        SessionCheck();
        include "../view/data-pelanggan.php";
    } else if($url == "data-barang"){
        SessionCheck();
        include "../view/data-barang.php";
    } else if($url == "data-transaksi"){
        SessionCheck();
        // Restrict access to data-transaksi for kasir role
        if(isKasir()) {
            echo "<script>
                alert('Maaf, akun dengan role Kasir tidak diizinkan untuk mengakses data transaksi');
                window.location.href = '{$_SERVER['PHP_SELF']}?u=home';
            </script>";
            exit;
        }
        include "../view/data-transaksi.php";
    } else if($url == "del-data-pelanggan"){
        SessionCheck();
        $id = (int)($_GET['id'] ?? 0);
        hapusPelanggan($id);
    } else if($url == "del-data-barang"){
        SessionCheck();
        $id = (int)($_GET['id'] ?? 0);
        hapusBarang($id);
    } else if($url == "del-data-transaksi"){
        SessionCheck();
        $id = (int)($_GET['id'] ?? 0);
        hapusTransaksi($id);
    } else if($url == "del-data-admin"){
        SessionCheck();
        $id = (int)($_GET['id'] ?? 0);
        hapusAdmin($id);
    } else if($url == "edit-pelanggan"){
        SessionCheck();
        include "../view/edit-pelanggan.php";
    } else if($url == "edit-barang"){
        SessionCheck();
        include "../view/edit-barang.php";
    } else if($url == "edit-transaksi"){
        SessionCheck();
        include "../view/edit-transaksi.php";
    } else if($url == "print-nota"){
        SessionCheck();
        $id_transaksi = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id_transaksi <= 0) {
            echo "<script>
                alert('ID Transaksi tidak valid');
                window.location.href = '{$_SERVER['PHP_SELF']}?u=data-transaksi';
            </script>";
            exit;
        }
        try {
            $pdo = db();
            $q = $pdo->prepare("SELECT 1 FROM transaksi WHERE id_transaksi=:id");
            $q->execute([':id'=>$id_transaksi]);
            if ($q->rowCount() === 0) {
                echo "<script>
                    alert('Transaksi tidak ditemukan');
                    window.location.href = '{$_SERVER['PHP_SELF']}?u=data-transaksi';
                </script>";
                exit;
            }
            cetakNota($id_transaksi);
        } catch (Exception $e) {
            echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href = '{$_SERVER['PHP_SELF']}?u=data-transaksi';
            </script>";
            exit;
        }
    } else if($url == "transaksi"){
        SessionCheck();
        if (isKasir()) {
            include "../view/transaksi.php";
        } else {
            echo "<script>
                alert('Maaf, hanya kasir yang dapat membuat transaksi baru');
                window.location.href = '{$_SERVER['PHP_SELF']}?u=home';
            </script>";
            exit;
        }
    }
    else if($url == "export-transaksi"){
        SessionCheck();
        // Fetch with PDO, keep ExportHelper the same
        $pdo = db();
        $stmt = $pdo->query("SELECT t.*, p.nama_pelanggan 
                             FROM transaksi t 
                             JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
                             ORDER BY t.tanggal DESC");
        $transactions = $stmt->fetchAll();
        include_once "../helpers/ExportHelper.php";
        ExportHelper::exportToExcel($transactions);
    }
}
else if(isset($_GET['chart_data'])) {
    header('Content-Type: application/json');
    $data_type = $_GET['chart_data'];
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    try {
        if ($data_type === 'monthly_sales') {
            $salesData = getMonthlySalesData($year);
            echo json_encode(array_values($salesData));
        } else if ($data_type === 'product_distribution') {
            $view_type = $_GET['view'] ?? 'count';
            $productData = getProductDistributionData($view_type);
            echo json_encode($productData);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data type requested']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>

