<?php
$currentPage = isset($_GET['u']) ? $_GET['u'] : 'home';
$baseUrl = "../controller/Controller.php";

// Only define helper functions if they don't already exist
if (!function_exists('isActive')) {
    function isActive($page, $current) {
        return $page === $current ? 'active' : '';
    }
}

if (!function_exists('isMenuOpen')) {
    function isMenuOpen($pages, $current) {
        return in_array($current, $pages) ? 'show' : '';
    }
}

// Don't redefine role-based access functions that are already in Function.php
// Use function_exists to check if they're already declared
if (!function_exists('isOwner') && function_exists('getUserRole')) {
    function isOwner() {
        return getUserRole() == 1;
    }
    
    function isAdmin() {
        return getUserRole() == 2;
    }
    
    function isKasir() {
        return getUserRole() == 3;
    }
}
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="../assets/images/faces-clipart/pic-2.png" alt="profile" />
          <span class="login-status online"></span>
          <!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2"><?= $_SESSION['nama_admin'];?></span>
          <span class="text-secondary text-small">
            <?= $_SESSION['username'];?> 
            <span class="badge bg-primary text-white ms-1" style="font-size: 9px;"><?= $_SESSION['nama_role'] ?? 'User'; ?></span>
          </span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    
    <!-- Dashboard - visible to all -->
    <li class="nav-item <?= isActive('home', $currentPage) ?>">
      <a class="nav-link" href="<?= $baseUrl . '?u=home'; ?>">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    
    <!-- Master Data - visible to all -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="<?= isMenuOpen(['data-barang', 'data-pelanggan'], $currentPage) ? 'true' : 'false' ?>">
        <span class="menu-title">Master Data</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-chart-bar menu-icon"></i>
      </a>
      <div class="collapse <?= isMenuOpen(['data-barang', 'data-pelanggan'], $currentPage) ?>" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link <?= isActive('data-barang', $currentPage) ?>" href="<?= $baseUrl . '?u=data-barang'; ?>">Data Barang</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link <?= isActive('data-pelanggan', $currentPage) ?>" href="<?= $baseUrl . '?u=data-pelanggan'; ?>">Data Pelanggan</a>
          </li>
        </ul>
      </div>
    </li>
    
    <!-- Transaksi - role-based -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#transaksi-menu" aria-expanded="<?= isMenuOpen(['transaksi', 'data-transaksi'], $currentPage) ? 'true' : 'false' ?>">
        <span class="menu-title">Transaksi</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-barcode-scan menu-icon"></i>
      </a>
      <div class="collapse <?= isMenuOpen(['transaksi', 'data-transaksi'], $currentPage) ?>" id="transaksi-menu">
        <ul class="nav flex-column sub-menu">
          <?php if (isKasir()): // Only Kasir can create transactions ?>
          <li class="nav-item">
            <a class="nav-link <?= isActive('transaksi', $currentPage) ?>" href="<?= $baseUrl . '?u=transaksi'; ?>">Transaksi Baru</a>
          </li>
          <?php endif; ?>
          <?php if (!isKasir()): // Hide data transaksi from Kasir role ?>
          <li class="nav-item">
            <a class="nav-link <?= isActive('data-transaksi', $currentPage) ?>" href="<?= $baseUrl . '?u=data-transaksi'; ?>">Data Transaksi</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </li>

    <!-- Akun - role-based -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#akun-menu" aria-expanded="<?= isMenuOpen(['data-admin'], $currentPage) ? 'true' : 'false' ?>">
        <span class="menu-title">Akun</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account menu-icon"></i>
      </a>
      <div class="collapse <?= isMenuOpen(['data-admin'], $currentPage) ?>" id="akun-menu">
        <ul class="nav flex-column sub-menu">
          <?php if (isOwner()): // Only Owner can manage admin accounts ?>
          <li class="nav-item">
            <a class="nav-link <?= isActive('data-admin', $currentPage) ?>" href="<?= $baseUrl . '?u=data-admin'; ?>">Manajemen Admin</a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $baseUrl . '?u=logout'; ?>">Logout</a>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</nav>