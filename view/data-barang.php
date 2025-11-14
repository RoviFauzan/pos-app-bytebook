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
    <style>
        /* Modern stock status styles that match the application theme */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.4rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            border-radius: 0.25rem;
            min-width: 60px;
            text-align: center;
        }
        
        .stock-normal {
            background-color: rgba(105, 205, 145, 0.2);
            color: #28a745;
            border: 1px solid rgba(105, 205, 145, 0.3);
        }
        
        .stock-low {
            background-color: rgba(255, 204, 112, 0.2);
            color: #fd7e14;
            border: 1px solid rgba(255, 204, 112, 0.3);
        }
        
        .stock-out {
            background-color: rgba(250, 92, 124, 0.2);
            color: #dc3545;
            border: 1px solid rgba(250, 92, 124, 0.3);
        }
        
        /* DataTable customization */
        #demo-table tr td:nth-child(6) {
            text-align: center;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(75, 73, 172, 0.05);
        }
        
        .modal .form-control {
            border-radius: 4px;
        }
    </style>
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
                  <i class="mdi mdi-laptop"></i>
                </span> Data Barang
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
                <h4 class="card-title">Data Barang</h4>
                <p class="card-description">
                    Berikut adalah data barang. 
                    <br>
                    <br>
                    <?php if (!isKasir() && !isOwner()) { ?>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#tambah-data-barang" class="btn btn-sm btn-primary rounded-5">
                        <i class="mdi mdi-plus"></i> Tambah Barang
                    </a>
                    <?php } ?>
                    <a href="#" onclick="location.reload();" class="btn btn-sm btn-info rounded-5">
                        <i class="mdi mdi-refresh"></i> Refresh
                    </a>
                </p>
                <div class="table-responsive">
                    <!-- data barang load -->
                    <table id="demo-table">
                        <thead>
                            <tr>
                                <th>
                                <b>#ID Barang</b>
                                </th>
                                <th>Nama Barang</th>
                                <th>Merk</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <?php if (!isKasir() && !isOwner()) { ?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="barang-body">
                            <!-- JS render -->
                        </tbody>
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
      const tbody = document.getElementById('barang-body');
      (async () => {
        const rows = await api.list('barang');
        tbody.innerHTML = rows.map(r=>{
          const stokBadge = r.stok <=0
            ? `<div class="stock-badge stock-out">0</div>`
            : (r.stok<=5
              ? `<div class="stock-badge stock-low">${r.stok}</div>`
              : `<div class="stock-badge stock-normal">${r.stok}</div>`);
          return `<tr>
            <td>${r.id_barang}</td>
            <td>${r.nama_barang}</td>
            <td>${r.merk||'-'}</td>
            <td class="text-end">${api.rupiah(r.harga_beli)}</td>
            <td class="text-end">${api.rupiah(r.harga_jual)}</td>
            <td>${stokBadge}</td>
            <td>
              <button class="btn btn-gradient-primary btn-sm" data-id="${r.id_barang}" onclick="editBarang(${r.id_barang})">
                <i class="mdi mdi-pencil"></i>
              </button>
              <a href="Controller.php?u=del-data-barang&id=${r.id_barang}" class="btn btn-gradient-danger btn-sm" onclick="return confirm('Hapus?')">
                <i class="mdi mdi-delete"></i>
              </a>
            </td>
          </tr>`;
        }).join('');
      })().catch(e=>{tbody.innerHTML=`<tr><td colspan="7">Error: ${e.message}</td></tr>`});
      window.editBarang = (id)=>alert('Form edit server-side (PHP) masih aktif. ID: '+id);
    </script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- <script src="../assets/js/dashboard.js"></script> -->
    <!-- End custom js for this page -->
  </body>
</html>