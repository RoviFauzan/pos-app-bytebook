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
                  <i class="mdi mdi-table"></i>
                </span> Data Transaksi
              </h3>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <!-- <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> -->
                  </li>
                </ul>
              </nav>
            </div>
            
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Data Transaksi</h4>
                        <a href="Controller.php?u=export-transaksi" class="btn btn-success btn-sm">
                            <i class="mdi mdi-file-excel"></i> Export Excel
                        </a>
                    </div>
                    <p class="card-description mb-3">
                      Daftar transaksi penjualan yang telah dilakukan.
                    </p>
                    
                    <div class="table-responsive">
                        <!-- data Transaksi load -->
                        <table id="demo-table">
                            <thead>
                                <tr>
                                    <th><b>#ID Transaksi</b></th>
                                    <th>Tanggal</th>
                                    <th>Total Pembelian</th>
                                    <th>Kembalian</th>
                                    <th>Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="transaksi-body"></tbody>
                        </table>
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
      const body = document.getElementById('transaksi-body');
      (async ()=>{
        const data = await api.list('transaksi');
        body.innerHTML = data.map(t=>`<tr>
          <td>${t.id_transaksi}</td>
          <td>${t.tanggal}</td>
            <td>${api.rupiah(t.total_pembelian)}</td>
            <td>${api.rupiah(t.kembalian)}</td>
            <td>${api.rupiah(t.bayar)}</td>
            <td>
              <a href="Controller.php?u=print-nota&id=${t.id_transaksi}" class="btn btn-info btn-sm"><i class="mdi mdi-eye"></i></a>
              <a href="Controller.php?u=del-data-transaksi&id=${t.id_transaksi}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')"><i class="mdi mdi-delete"></i></a>
            </td>
        </tr>`).join('');
      })().catch(e=>body.innerHTML=`<tr><td colspan="6">Error: ${e.message}</td></tr>`);
    </script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- <script src="../assets/js/dashboard.js"></script> -->
    <!-- End custom js for this page -->
  </body>
</html>