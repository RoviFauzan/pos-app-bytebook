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
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
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
                  <i class="mdi mdi-account-multiple"></i>
                </span> Data Pelanggan
              </h3>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <!-- <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> -->
                  </li>
                </ul>
              </nav>
            </div>
            
            <div class="row card rounded-5">
                <div class="card-body">
                <h4 class="card-title">Data Pelanggan</h4>
                <p class="card-description">
                    Berikut adalah data Pelanggan. 
                    <br>
                    <br>
                    <?php if (!isKasir() && !isOwner()) { ?>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#tambah-data-pelanggan" class="btn btn-sm btn-primary rounded-5">
                        <i class="mdi mdi-plus"></i> Tambah Pelanggan
                    </a>
                    <?php } ?>
                    <a href="#" onclick="location.reload();" class="btn btn-sm btn-info rounded-5">
                        <i class="mdi mdi-refresh"></i> Refresh
                    </a>
                </p>
                <div class="table-responsive">
                    <!-- data Pelanggan load -->
                    <table id="demo-table">
                        <thead>
                            <tr>
                                <th>
                                <b>#ID Pelanggan</b>
                                </th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <?php if (!isKasir() && !isOwner()) { ?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="pelanggan-body"></tbody>
                    </table>

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
    <script src="../assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <!-- <script src="../assets/js/misc.js"></script> -->
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/todolist.js"></script>
    <script src="../assets/js/jquery.cookie.js"></script>
    <script type="module">
      import api from '../assets/js/supabase-client.js';
      const body = document.getElementById('pelanggan-body');
      (async ()=>{
        const data = await api.list('pelanggan');
        body.innerHTML = data.map(p=>`<tr>
          <td>${p.id_pelanggan}</td>
          <td>${p.nama_pelanggan}</td>
          <td>${p.no_hp}</td>
          <td>${p.alamat}</td>
          <td>${p.email}</td>
          <td>
            <button class="btn btn-primary btn-sm" onclick="editP(${p.id_pelanggan})"><i class="mdi mdi-pencil"></i></button>
            <a href="Controller.php?u=del-data-pelanggan&id=${p.id_pelanggan}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')"><i class="mdi mdi-delete"></i></a>
          </td>
        </tr>`).join('');
      })().catch(e=>body.innerHTML=`<tr><td colspan="6">Error: ${e.message}</td></tr>`);
      window.editP = id => alert('Gunakan form PHP edit (belum dipindah). ID: '+id);
    </script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- <script src="../assets/js/dashboard.js"></script> -->
    <!-- End custom js for this page -->
  </body>
</html>