<?php
// Include database connection (PDO)
include_once "../controller/Database.php";
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
    <script type="module" src="../assets/js/supabase-client.js"></script>
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
                        <tbody id="admin-body"></tbody>
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
    <script type="module">
      import api from '../assets/js/supabase-client.js';
      const body = document.getElementById('admin-body');
      (async ()=>{
        const admins = await api.list('admin');
        const rolesMap = {};
        (await api.list('role')).forEach(r=>rolesMap[r.id_role]=r.nama_role);
        body.innerHTML = admins.map(a=>{
          const current = (window?.PHPSESSION_ID_ADMIN && window.PHPSESSION_ID_ADMIN==a.id_admin);
          const statusBadge = current ? '<span class="badge bg-gradient-success">Aktif</span>' : '<span class="badge bg-light text-dark">Tidak Aktif</span>';
          return `<tr>
            <td>${a.id_admin}</td>
            <td>${a.nama_admin}</td>
            <td>${a.username}</td>
            <td>${rolesMap[a.id_role]||'-'}</td>
            <td>${statusBadge}</td>
            <td>
              <a href="#" class="btn btn-gradient-info btn-sm" onclick="alert('Edit masih via PHP form')"><i class="mdi mdi-pencil"></i></a>
              <a href="Controller.php?u=del-data-admin&id=${a.id_admin}" class="btn btn-gradient-danger btn-sm" onclick="return confirm('Hapus?')"><i class="mdi mdi-delete"></i></a>
            </td>
          </tr>`;
        }).join('');
      })().catch(e=>body.innerHTML=`<tr><td colspan="6">Error: ${e.message}</td></tr>`);
    </script>
    <!-- End custom js for this page -->
  </body>
        </div>
        <!-- main-panel ends -->      </div>      <!-- page-body-wrapper ends -->    </div>    <!-- container-scroller -->    <!-- plugins:js -->    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>    <!-- endinject -->    <!-- Plugin js for this page -->    <script src="../assets/vendors/simple-datatables/simple-datatables.js"></script>    <!-- End plugin js for this page -->    <!-- inject:js -->    <script src="../assets/js/off-canvas.js"></script>    <script src="../assets/js/hoverable-collapse.js"></script>    <script src="../assets/js/settings.js"></script>    <script src="../assets/js/todolist.js"></script>    <!-- endinject -->    <!-- Custom js for this page -->    <script>      // Initialize DataTable      document.addEventListener('DOMContentLoaded', function() {        // Initialize DataTable        const dataTable = new simpleDatatables.DataTable("#admin-table", {          perPage: 10        });                // Password toggle functionality        const togglePasswordBtns = document.querySelectorAll('.toggle-password');        togglePasswordBtns.forEach(btn => {          btn.addEventListener('click', function() {            const targetId = this.getAttribute('data-target');            const passwordInput = document.getElementById(targetId);            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';            passwordInput.setAttribute('type', type);                        const icon = this.querySelector('i');            if (type === 'password') {              icon.classList.remove('mdi-eye-off');              icon.classList.add('mdi-eye');            } else {              icon.classList.remove('mdi-eye');              icon.classList.add('mdi-eye-off');            }          });        });      });            // Confirm delete admin      function confirmDelete(id) {        Swal.fire({          title: 'Apakah Anda yakin?',          text: "Admin yang dihapus tidak dapat dipulihkan!",          icon: 'warning',          showCancelButton: true,          confirmButtonColor: '#d33',          cancelButtonColor: '#3085d6',          confirmButtonText: 'Ya, Hapus!',          cancelButtonText: 'Batal'        }).then((result) => {          if (result.isConfirmed) {            window.location.href = 'Controller.php?u=del-data-admin&id=' + id;          }        });      }    </script>    <!-- End custom js for this page -->  </body></html>