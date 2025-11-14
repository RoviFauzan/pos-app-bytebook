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
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
    <style>
      .stock-item {
        background-color: #f8d7da;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
      }
      
      /* Custom loading styles */
      .custom-loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }
      
      .custom-loading-container {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 3px 20px rgba(0,0,0,0.2);
        text-align: center;
        max-width: 400px;
        width: 90%;
      }
      
      .custom-spinner {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #4B49AC;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: customSpin 2s linear infinite;
        margin: 0 auto 20px;
      }
      
      .error-message {
        color: #d9534f;
        margin-top: 10px;
        padding: 10px;
        background-color: #f8d7da;
        border-radius: 5px;
        display: none;
        text-align: left;
      }
      
      @keyframes customSpin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      
      /* Progress bar styles */
      .processing-progress {
        width: 100%;
        height: 6px;
        background-color: #ddd;
        border-radius: 3px;
        margin-top: 15px;
        overflow: hidden;
      }
      
      .progress-bar {
        height: 100%;
        background-color: #4B49AC;
        width: 0;
        border-radius: 3px;
        transition: width 0.5s ease;
      }
    </style>
  </head>
  <body>
    <!-- Progress overlay - initially hidden -->
    <div class="custom-loading-overlay" id="processingOverlay" style="display: none;">
      <div class="custom-loading-container">
        <div class="custom-spinner"></div>
        <h3>Memproses Transaksi</h3>
        <p>Mohon tunggu sebentar...</p>
        <p id="processingStage">Mempersiapkan data...</p>
        <div class="processing-progress">
          <div class="progress-bar" id="processingProgressBar"></div>
        </div>
        <div class="error-message" id="processingError">
          <strong>Error:</strong> <span id="errorText"></span>
          <div class="mt-2">
            <button class="btn btn-sm btn-outline-danger" onclick="window.location.reload()">Coba Lagi</button>
          </div>
        </div>
      </div>
    </div>
    
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
                  </span> Transaksi Baru
                </h3>
                <nav aria-label="breadcrumb">
                  <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"></li>
                  </ul>
                </nav>
              </div>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">Transaksi</h4>
                  <hr class="text-dark">
                  
                  <form action="Controller.php" method="POST" id="form-transaksi"> 
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="tanggal">Tanggal </label>
                        </div>
                        <div class="col-md-8">
                            <input type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i'); ?>" name="tanggal_transaksi" readonly>
                        </div>
                        <div class="col-md-4 mt-4">
                            <label for="nama_pelanggan">Pilih Pelanggan</label>
                        </div>
                        <div class="col-md-8 mt-4">
                            <select class="form-select" name="id_pelanggan">
                                <?php
                                $data_pelanggan = getDataPelanggan();
                                foreach ($data_pelanggan as $fetch_data) {
                                ?>
                                <option value="<?= htmlspecialchars($fetch_data['id_pelanggan']); ?>"><?= htmlspecialchars($fetch_data['nama_pelanggan']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="col-12 text-center mt-4 mb-4">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalBarang">
                                <i class="mdi mdi-cart-plus"></i> Tambah Barang
                            </button>
                        </div>
                        
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table-barang">
                                    <thead class="bg-light">    
                                        <tr>
                                            <th>ID Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Items will be added here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total</th>
                                            <th id="total-harga" class="text-end">Rp 0</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="2"></textarea>
                        </div>

                        <input type="hidden" name="total_pembelian" id="total_pembelian" value="0">
                        <input type="hidden" name="detail_transaksi[]" id="detail_transaksi" value="[]">

                        <div class="col-12 text-center mt-4">
                            <button type="button" class="btn btn-success" id="btn-bayar" onclick="validateTransaction()">
                                <i class="mdi mdi-cash-multiple"></i> Proses Pembayaran
                            </button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="modalBarang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>
                        <select class="form-select" id="select-barang">
                            <option value="" selected disabled>-- Pilih Barang --</option>
                            <?php 
                            $barang = getDataBarang();
                            foreach ($barang as $key) {
                                $disabled = ($key['stok'] <= 0) ? 'disabled' : '';
                                $stockInfo = ($key['stok'] <= 0) ? ' - Stok Habis' : ' - Stok: ' . $key['stok'];
                            ?>
                            <option value="<?= $key['id_barang']; ?>" 
                                    data-nama="<?= htmlspecialchars($key['nama_barang']); ?>"
                                    data-harga="<?= $key['harga_jual']; ?>" 
                                    data-stok="<?= $key['stok']; ?>"
                                    <?= $disabled ?>>
                                <?= $key['nama_barang']; ?><?= $stockInfo; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" id="qty-barang" class="form-control" min="1" value="1">
                        <div class="form-text" id="stok-info"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-tambahkan-barang">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div class="modal fade" id="modalBayar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h5>Total Pembayaran</h5>
                        <h3 id="modal-total">Rp 0</h3>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Dibayar</label>
                        <input type="number" id="input-bayar" class="form-control" min="0">
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            <button class="btn btn-sm btn-outline-secondary nominal-btn" data-nominal="50000">50.000</button>
                            <button class="btn btn-sm btn-outline-secondary nominal-btn" data-nominal="100000">100.000</button>
                            <button class="btn btn-sm btn-outline-secondary nominal-btn" data-nominal="200000">200.000</button>
                            <button class="btn btn-sm btn-outline-secondary nominal-btn" data-nominal="500000">500.000</button>
                            <button class="btn btn-sm btn-outline-secondary nominal-btn" data-nominal="1000000">1.000.000</button>
                            <button class="btn btn-sm btn-outline-secondary nominal-btn" data-nominal="5000000">5.000.000</button>
                            <button class="btn btn-sm btn-outline-secondary nominal-btn" data-nominal="10000000">10.000.000</button>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <h5>Kembalian</h5>
                        <h3 id="kembalian">Rp 0</h3>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btn-proses-bayar">Proses</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let totalHarga = 0;
        let itemCounter = 0;
        const modalBarang = new bootstrap.Modal(document.getElementById('modalBarang'));
        const modalBayar = new bootstrap.Modal(document.getElementById('modalBayar'));
        const items = new Map();

        // Handle barang selection
        document.getElementById('select-barang').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const stok = parseInt(option.dataset.stok);
            const stokInfo = document.getElementById('stok-info');
            
            stokInfo.textContent = `Stok tersedia: ${stok}`;
            
            if (stok <= 5) {
                stokInfo.classList.add('text-warning');
            } else {
                stokInfo.classList.remove('text-warning');
            }
            
            // Reset qty input
            document.getElementById('qty-barang').value = 1;
            document.getElementById('qty-barang').max = stok;
        });

        // Handle quantity input validation
        document.getElementById('qty-barang').addEventListener('input', function() {
            const select = document.getElementById('select-barang');
            const option = select.options[select.selectedIndex];
            const stok = parseInt(option.dataset.stok);
            
            if (parseInt(this.value) > stok) {
                this.value = stok;
            }
            
            if (parseInt(this.value) < 1) {
                this.value = 1;
            }
        });

        // Add item to transaction
        document.getElementById('btn-tambahkan-barang').addEventListener('click', function() {
            const select = document.getElementById('select-barang');
            if (select.value === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Barang',
                    text: 'Silakan pilih barang terlebih dahulu',
                    confirmButtonColor: '#4B49AC'
                });
                return;
            }

            const option = select.options[select.selectedIndex];
            const id = select.value;
            const nama = option.dataset.nama;
            const harga = parseInt(option.dataset.harga);
            const stok = parseInt(option.dataset.stok);
            const qty = parseInt(document.getElementById('qty-barang').value || 0);

            if (qty <= 0 || qty > stok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Jumlah Tidak Valid',
                    text: `Jumlah harus antara 1 dan ${stok}`,
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Check if item already in cart
            if (items.has(id)) {
                const existingItem = items.get(id);
                const newQty = existingItem.qty + qty;
                
                if (newQty > stok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Cukup',
                        text: `Total jumlah melebihi stok yang tersedia (${stok})`,
                        confirmButtonColor: '#d33'
                    });
                    return;
                }
                
                // Update existing row
                existingItem.qty = newQty;
                existingItem.subtotal = harga * newQty;
                
                document.querySelector(`#item-${id} .item-qty`).textContent = newQty;
                document.querySelector(`#item-${id} .item-subtotal`).textContent = formatRupiah(existingItem.subtotal);
            } else {
                // Add new item
                const itemId = id;
                const uniqueId = `item-${itemId}`;
                const subtotal = harga * qty;
                
                items.set(id, {
                    id: itemId,
                    nama: nama,
                    harga: harga,
                    qty: qty,
                    subtotal: subtotal
                });
                
                const tbody = document.querySelector('#table-barang tbody');
                const row = tbody.insertRow();
                row.id = uniqueId;
                
                row.innerHTML = `
                    <td>${itemId}</td>
                    <td>${nama}</td>
                    <td class="text-end">${formatRupiah(harga)}</td>
                    <td class="text-center item-qty">${qty}</td>
                    <td class="text-end item-subtotal">${formatRupiah(subtotal)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem('${itemId}')">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                `;
            }
            
            // Update total
            updateTotal();

            // Close modal and reset form
            modalBarang.hide();
            document.getElementById('select-barang').selectedIndex = 0;
            document.getElementById('qty-barang').value = 1;
            document.getElementById('stok-info').textContent = '';
        });

        // Process payment button
        document.getElementById('btn-bayar').addEventListener('click', function() {
            if (items.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Silakan tambahkan barang terlebih dahulu',
                    confirmButtonColor: '#4B49AC'
                });
                return;
            }
            
            document.getElementById('modal-total').textContent = formatRupiah(totalHarga);
            document.getElementById('input-bayar').value = '';
            document.getElementById('kembalian').textContent = formatRupiah(0);
            modalBayar.show();
        });

        // Handle payment amount input
        document.getElementById('input-bayar').addEventListener('input', function() {
            const bayar = parseFloat(this.value) || 0;
            const kembalian = bayar - totalHarga;
            document.getElementById('kembalian').textContent = formatRupiah(kembalian);
            const btnProsesBayar = document.getElementById('btn-proses-bayar');
            if (bayar < totalHarga) {
                btnProsesBayar.disabled = true;
                document.getElementById('kembalian').classList.add('text-danger');
            } else {
                btnProsesBayar.disabled = false;
                document.getElementById('kembalian').classList.remove('text-danger');
            }
        });

        // Nominal buttons
        document.querySelectorAll('.nominal-btn').forEach(button => {
            button.addEventListener('click', function() {
                const nominal = parseInt(this.dataset.nominal);
                const currentValue = parseFloat(document.getElementById('input-bayar').value) || 0;
                // Add the button value to the existing amount instead of replacing it
                document.getElementById('input-bayar').value = currentValue + nominal;
                document.getElementById('input-bayar').dispatchEvent(new Event('input'));
            });
        });

        // Process payment
        document.getElementById('btn-proses-bayar').addEventListener('click', function() {
            const bayar = parseFloat(document.getElementById('input-bayar').value) || 0;
            
            if (bayar < totalHarga) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Kurang',
                    text: 'Jumlah pembayaran kurang dari total belanja',
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            // Set form values
            document.getElementById('total_pembelian').value = totalHarga;
            const detailTransaksi = [];
            items.forEach(item => {
                detailTransaksi.push({
                    id_barang: item.id,
                    qty: item.qty
                });
            });
            
            document.getElementById('detail_transaksi').value = JSON.stringify(detailTransaksi);
            
            // Show processing overlay
            document.getElementById('processingOverlay').style.display = 'flex';
            document.getElementById('processingStage').textContent = 'Memproses transaksi...';
            updateProgressBar(50);
            
            modalBayar.hide();
            
            // Use traditional form submission for maximum compatibility
            const form = document.getElementById('form-transaksi');
            
            // Add the required fields that might be missing using setAttribute for better compatibility
            form.setAttribute('action', 'Controller.php');
            form.setAttribute('method', 'POST');
            
            // Create or update hidden inputs
            let bayarInput = document.querySelector('input[name="bayar"]');
            if (!bayarInput) {
                bayarInput = document.createElement('input');
                bayarInput.setAttribute('type', 'hidden');
                bayarInput.setAttribute('name', 'bayar');
                form.appendChild(bayarInput);
            }
            bayarInput.value = bayar;
            
            let submitInput = document.querySelector('input[name="tambah-transaksi"]');
            if (!submitInput) {
                submitInput = document.createElement('input');
                submitInput.setAttribute('type', 'hidden');
                submitInput.setAttribute('name', 'tambah-transaksi');
                form.appendChild(submitInput);
            }
            submitInput.value = 'true';
            
            // Direct synchronous submission
            form.submit();
        });

        // Function to update progress bar
        function updateProgressBar(percent) {
            const progressBar = document.getElementById('processingProgressBar');
            progressBar.style.width = percent + '%';
        }
        
        // Remove item function
        window.removeItem = function(id) {
            if (items.has(id)) {
                items.delete(id);
                document.getElementById(`item-${id}`).remove();
                updateTotal();
            }
        }

        // Validate transaction before payment
        window.validateTransaction = function() {
            if (items.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Silakan tambahkan barang terlebih dahulu',
                    confirmButtonColor: '#4B49AC'
                });
                return;
            }
            
            document.getElementById('modal-total').textContent = formatRupiah(totalHarga);
            document.getElementById('input-bayar').value = '';
            document.getElementById('kembalian').textContent = formatRupiah(0);
            modalBayar.show();
        }

        // Update total function
        function updateTotal() {
            totalHarga = 0;
            items.forEach(item => {
                totalHarga += item.subtotal;
            });
            
            document.getElementById('total-harga').textContent = formatRupiah(totalHarga);
        }

        // Format currency function
        window.formatRupiah = function(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }
    });

    // Add global error handler
    window.addEventListener('error', function(event) {
        console.error('Global error caught:', event.error);
        
        // If processing overlay is visible, show error in it
        if (document.getElementById('processingOverlay').style.display === 'flex') {
            document.getElementById('processingError').style.display = 'block';
            document.getElementById('errorText').textContent = 'Javascript error: ' + event.message;
            document.getElementById('processingStage').textContent = 'Proses gagal!';
        }
    });

    // Add network error detection
    window.addEventListener('offline', function() {
        if (document.getElementById('processingOverlay').style.display === 'flex') {
            document.getElementById('processingError').style.display = 'block';
            document.getElementById('errorText').textContent = 'Koneksi internet terputus. Silakan periksa koneksi Anda.';
        }
    });
    </script>

  </body>
</html>