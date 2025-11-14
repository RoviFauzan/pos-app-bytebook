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
                            <tbody>
                                <?php
                                $dataTransaksi = getDataTransaksi(); // Fungsi untuk mengambil data transaksi dari database
                                foreach ($dataTransaksi as $transaksi) {
                                ?>
                                    <tr>
                                        <td><?php echo $transaksi['id_transaksi']; ?></td>
                                        <td><?php echo $transaksi['tanggal']; ?></td>
                                        <td><?php echo $transaksi['total_pembelian']; ?></td>
                                        <td><?php echo $transaksi['kembalian']; ?></td>
                                        <td><?php echo $transaksi['bayar']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detail-Transaksi-<?php echo $transaksi['id_transaksi']; ?>"><i class="mdi mdi-eye"></i></a>
                                            <a href="Controller.php?u=del-data-transaksi&id=<?php echo $transaksi['id_transaksi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus data ini?');"><i class="mdi mdi-delete"></i></a>

                                            <!-- Modal Detail Transaksi -->
                                            <div class="modal fade" id="detail-Transaksi-<?php echo $transaksi['id_transaksi']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailTransaksiLabel-<?php echo $transaksi['id_transaksi']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailTransaksiLabel-<?php echo $transaksi['id_transaksi']; ?>">Detail Transaksi #<?php echo $transaksi['id_transaksi']; ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Tanggal Pembelian: <?php echo $transaksi['tanggal']; ?></p>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#ID Barang</th>
                                                                        <th>Nama Barang</th>
                                                                        <th>Qty</th>
                                                                        <th>Harga Jual</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $detailTransaksi = getDetailTransaksiByTransaksiId($transaksi['id_transaksi']);
                                                                    foreach ($detailTransaksi as $detail) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $detail['id_barang']; ?></td>
                                                                            <td><?php echo $detail['nama_barang']; ?></td>
                                                                            <td><?php echo $detail['qty']; ?></td>
                                                                            <td><?php echo $detail['harga_jual']; ?></td>
                                                                            <td><?php echo $detail['total']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                            <p>Pembayaran: <b>Rp  <?php echo number_format($transaksi['bayar']); ?></b></p>
                                                            <p>Kembalian: <b>Rp <?php echo number_format($transaksi['kembalian']); ?></b></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            <a href="Controller.php?u=print-nota&id=<?php echo $transaksi['id_transaksi']; ?>" class="btn btn-primary">Print Nota</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal Detail Transaksi -->
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
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- <script src="../assets/js/dashboard.js"></script> -->
    <!-- End custom js for this page -->
  </body>
</html>