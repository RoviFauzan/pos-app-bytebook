<?php
// Include database connection (PDO)
include_once "../controller/Database.php";

// Fetch roles for dropdowns using PDO
$roles = [];
try {
  $stmt = db()->query("SELECT * FROM role ORDER BY id_role");
  $roles = $stmt->fetchAll();
} catch (Throwable $e) {
  $roles = [];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kasir App</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendors/simple-datatables/demo.css">
    <link rel="stylesheet" href="../assets/vendors/simple-datatables/style.css">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">

      <!-- partial header -->
      <?php include "header.php";?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial sidebar -->
        <?php include "sidebar.php";?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-account-key"></i>
                </span> Manajemen Admin
              </h3>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Pengaturan Akun Administrator
                  </li>
                </ul>
              </nav>
            </div>
            
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                      <h4 class="card-title mb-0">Daftar Administrator</h4>
                      <a href="#" data-bs-toggle="modal" data-bs-target="#tambahAdminModal" class="btn btn-gradient-primary">
                        <i class="mdi mdi-account-plus"></i> Tambah Admin
                      </a>
                    </div>
                    <p class="card-description">
                      Pengguna dengan hak akses administrator sistem.
                    </p>
                    <div class="table-responsive">
                      <table class="table table-hover" id="admin-table">
                        <thead>
                          <tr>
                            <th>ID Admin</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                          $dataAdmin = getDataAdmin();
                          foreach ($dataAdmin as $admin) {
                            $isCurrentUser = ($_SESSION['id_admin'] == $admin['id_admin']);
                            $statusClass = $isCurrentUser ? 'badge bg-gradient-success' : 'badge bg-light text-dark';
                            $statusText = $isCurrentUser ? 'Aktif' : 'Tidak Aktif';
                        ?>
                          <tr>
                            <td><?= $admin['id_admin']; ?></td>
                            <td><?= $admin['nama_admin']; ?></td>
                            <td><?= $admin['username']; ?></td>
                            <td><?= $admin['nama_role']; ?></td> <!-- Changed from $admin['role'] to $admin['nama_role'] -->
                            <td><span class="<?= $statusClass ?>"><?= $statusText ?></span></td>
                            <td>
                              <?php if (!$isCurrentUser): ?>
                                <a href="#" class="btn btn-gradient-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#edit-admin-<?= $admin['id_admin']; ?>">
                                  <i class="mdi mdi-pencil"></i>
                                </a>
                                <a href="#" class="btn btn-gradient-danger btn-sm" onclick="confirmDelete(<?= $admin['id_admin']; ?>)">
                                  <i class="mdi mdi-delete"></i>
                                </a>
                              
                                <!-- Modal Edit Admin -->
                                <div class="modal fade" id="edit-admin-<?= $admin['id_admin']; ?>" tabindex="-1" aria-labelledby="editAdminLabel<?= $admin['id_admin']; ?>" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="editAdminLabel<?= $admin['id_admin']; ?>">Edit Admin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        <form action="Controller.php" method="POST">
                                          <input type="hidden" name="id_admin" value="<?= $admin['id_admin']; ?>">
                                          
                                          <div class="form-group mb-3">
                                            <label for="edit_nama_admin_<?= $admin['id_admin']; ?>">Nama Admin</label>
                                            <input type="text" class="form-control" id="edit_nama_admin_<?= $admin['id_admin']; ?>" name="nama_admin" value="<?= $admin['nama_admin']; ?>" required>
                                          </div>
                                          
                                          <div class="form-group mb-3">
                                            <label for="edit_username_<?= $admin['id_admin']; ?>">Username</label>
                                            <input type="text" class="form-control" id="edit_username_<?= $admin['id_admin']; ?>" name="username" value="<?= $admin['username']; ?>" required>
                                          </div>
                                          
                                          <div class="form-group mb-3">
                                            <label for="edit_password_<?= $admin['id_admin']; ?>">Password Baru</label>
                                            <div class="input-group">
                                              <input type="password" class="form-control" id="edit_password_<?= $admin['id_admin']; ?>" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                              <button class="btn btn-outline-secondary toggle-password" type="button" data-target="edit_password_<?= $admin['id_admin']; ?>">
                                                <i class="mdi mdi-eye"></i>
                                              </button>
                                            </div>
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                                          </div>

                                          <div class="form-group mb-3">
                                            <label for="edit_role_<?= $admin['id_admin']; ?>">Role</label>
                                            <select class="form-control" id="edit_role_<?= $admin['id_admin']; ?>" name="id_role" required>
                                              <?php foreach ($roles as $role): ?>
                                                <option value="<?= $role['id_role']; ?>" <?= $role['id_role'] == $admin['id_role'] ? 'selected' : ''; ?>>
                                                  <?= $role['nama_role']; ?>
                                                </option>
                                              <?php endforeach; ?>
                                            </select>
                                          </div>
                                          
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-gradient-primary" name="edit-admin">Simpan Perubahan</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php else: ?>
                                <button class="btn btn-gradient-secondary btn-sm" disabled title="Tidak bisa mengubah admin aktif dari sini">
                                  <i class="mdi mdi-account-check"></i> Admin Aktif
                                </button>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Modal Tambah Admin -->
            <div class="modal fade" id="tambahAdminModal" tabindex="-1" role="dialog" aria-labelledby="tambahAdminModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="tambahAdminModalLabel">Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="Controller.php" method="POST">
                      <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                      </div>
                      <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                      </div>
                      <div class="form-group mb-3">
                        <label for="nama_admin">Nama Admin</label>
                        <input type="text" class="form-control" id="nama_admin" name="nama_admin" required>
                      </div>
                      <div class="form-group mb-3">
                        <label for="id_role">Role</label>
                        <select class="form-control" id="id_role" name="id_role" required>
                          <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id_role']; ?>"><?= $role['nama_role']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-gradient-primary" name="tambah-admin">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Current User Profile Card -->
            <div class="row">
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Profil Admin Aktif</h4>
                    <p class="card-description">
                      Detail akun administrator yang sedang login
                    </p>
                    
                    <div class="d-flex align-items-center mb-4">
                      <div class="profile-image me-3">
                        <img src="../assets/images/faces-clipart/pic-2.png" alt="profile" class="rounded-circle" width="70">
                      </div>
                      <div>
                        <h5 class="mb-0"><?= $_SESSION['nama_admin']; ?></h5>
                        <small class="text-muted">Username: <?= $_SESSION['username']; ?></small>
                      </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                      <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#ubah-akun-admin">
                        <i class="mdi mdi-account-edit"></i> Edit Profil
                      </button>
                      <a href="<?= $_SERVER['PHP_SELF'] . '?u=logout'; ?>" class="btn btn-gradient-danger">
                        <i class="mdi mdi-logout"></i> Logout
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Keamanan Akun</h4>
                    <p class="card-description">
                      Panduan keamanan akun administrator
                    </p>
                    
                    <div class="security-tips">
                      <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-shield-check text-success me-2" style="font-size: 24px;"></i>
                        <div>
                          <h6 class="mb-0">Jaga Kerahasiaan Password</h6>
                          <small class="text-muted">Jangan pernah membagikan password kepada orang lain</small>
                        </div>
                      </div>
                      
                      <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-lock text-info me-2" style="font-size: 24px;"></i>
                        <div>
                          <h6 class="mb-0">Gunakan Password yang Kuat</h6>
                          <small class="text-muted">Kombinasi huruf, angka, dan simbol</small>
                        </div>
                      </div>
                      
                      <div class="d-flex align-items-center">
                        <i class="mdi mdi-logout-variant text-warning me-2" style="font-size: 24px;"></i>
                        <div>
                          <h6 class="mb-0">Selalu Logout</h6>
                          <small class="text-muted">Setelah selesai menggunakan sistem</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <?php include "footer.php";?>
          <?php include "modals.php";?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../assets/vendors/simple-datatables/simple-datatables.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script>
      // Initialize DataTable
      document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        const dataTable = new simpleDatatables.DataTable("#admin-table", {
          perPage: 10
        });
        
        // Password toggle functionality
        const togglePasswordBtns = document.querySelectorAll('.toggle-password');
        togglePasswordBtns.forEach(btn => {
          btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (type === 'password') {
              icon.classList.remove('mdi-eye-off');
              icon.classList.add('mdi-eye');
            } else {
              icon.classList.remove('mdi-eye');
              icon.classList.add('mdi-eye-off');
            }
          });
        });
      });
      
      // Confirm delete admin
      function confirmDelete(id) {
        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: "Admin yang dihapus tidak dapat dipulihkan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = 'Controller.php?u=del-data-admin&id=' + id;
          }
        });
      }
    </script>
    <!-- End custom js for this page -->
  </body>
</html>
