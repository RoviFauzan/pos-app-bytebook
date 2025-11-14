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
                        <tbody>
                        <?php
                            $dataBarang = getDataBarang();
                            foreach ($dataBarang as $barang) {
                                // Determine stock status badge with just the number
                                if ($barang['stok'] <= 0) {
                                    $stockBadge = '<div class="stock-badge stock-out">0</div>';
                                } elseif ($barang['stok'] <= 5) {
                                    $stockBadge = '<div class="stock-badge stock-low">' . $barang['stok'] . '</div>';
                                } else {
                                    $stockBadge = '<div class="stock-badge stock-normal">' . $barang['stok'] . '</div>';
                                }
                            ?>
                                <tr>
                                    <td><?php echo $barang['id_barang']; ?></td>
                                    <td><?php echo $barang['nama_barang']; ?></td>
                                    <td><?php echo $barang['merk']; ?></td>
                                    <td>Rp <?php echo number_format($barang['harga_beli'], 0, ',', '.'); ?></td>
                                    <td>Rp <?php echo number_format($barang['harga_jual'], 0, ',', '.'); ?></td>
                                    <td data-stock-value="<?php echo $barang['stok']; ?>"><?php echo $stockBadge; ?></td>
                                    <?php if (!isKasir() && !isOwner()) { ?>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="btn btn-gradient-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#edit-barang-<?php echo $barang['id_barang']; ?>">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="Controller.php?u=del-data-barang&id=<?php echo $barang['id_barang']; ?>" class="btn btn-gradient-danger btn-sm" 
                                               onclick="return confirm('Apakah anda yakin akan menghapus data ini?');">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </div>
                                        
                                        <!-- Modal Edit Barang -->
<div class="modal fade" id="edit-barang-<?php echo $barang['id_barang']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBarangLabel">Edit Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="Controller.php" method="post">
                    <input type="hidden" name="id_barang" value="<?php echo $barang['id_barang']; ?>">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" value="<?php echo $barang['nama_barang']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="merk" class="form-label">Merk</label>
                        <input type="text" class="form-control" name="merk" value="<?php echo $barang['merk']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="harga_beli" class="form-label">Harga Beli</label>
                        <input type="text" class="form-control" name="harga_beli" value="<?php echo $barang['harga_beli']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="harga_jual" class="form-label">Harga Jual</label>
                        <input type="text" class="form-control" name="harga_jual" value="<?php echo $barang['harga_jual']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="text" class="form-control" name="stok" value="<?php echo $barang['stok']; ?>">
                    </div>
                    <div class="modal-footer mt-5">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary" name="edit-barang">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit Barang -->

                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
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
            import {DataTable} from "../assets/vendors/simple-datatables/module.js"
            window.dt = new DataTable("#demo-table", {
                perPageSelect: [5, 10, 15, ["All", -1]],
                columns: [
                    {
                        select: 2,
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 3,
                        sortSequence: ["desc"]
                    },
                    {
                        select: 4,
                        cellClass: "green",
                        headerClass: "red"
                    }
                ]
            })
        </script>
    <script>
        // Add this to your existing script
        window.addEventListener('DOMContentLoaded', function() {
            // Enhance DataTables to sort stock column correctly
            document.querySelector('#demo-table').addEventListener('click', function(e) {
                if (e.target.closest('th:nth-child(6)')) {
                    // Using data-stock-value attribute for accurate sorting
                    window.dt.order([5, 'asc']); // Sort by stock column (0-based index)
                }
            });
            
            // Format currency values
            document.querySelectorAll('#demo-table tbody tr td:nth-child(4), #demo-table tbody tr td:nth-child(5)').forEach(cell => {
                if (!cell.textContent.includes('Rp')) {
                    cell.textContent = 'Rp ' + parseFloat(cell.textContent).toLocaleString('id-ID');
                }
            });
        });
    </script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- <script src="../assets/js/dashboard.js"></script> -->
    <!-- End custom js for this page -->
  </body>
</html>