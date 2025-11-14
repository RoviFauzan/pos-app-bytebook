<!-- Modal Tambah Data Barang -->
<div class="modal fade" id="tambah-data-barang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="Controller.php" method="post">
          <div class="row mt-2">
            <div class="col-3">
              <label for="nama_barang">Nama Barang</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="nama_barang" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="harga_beli">Harga Beli</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="harga_beli" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="harga_jual">Harga Jual</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="harga_jual" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="stok">Stok</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="stok" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="merk">Merk</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="merk" required>
            </div>
          </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batalkan</button>
        <button class="btn btn-primary rounded-pill" type="submit" name="tambah-data-barang" href="#">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End modal -->

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="tambah-data-pelanggan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Pelanggan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="Controller.php" method="post">
          <div class="row mt-2">
            <div class="col-3">
              <label for="nama_pelanggan"> Nama Pelanggan </label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="nama_pelanggan" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="no_hp">No. HP</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="no_hp" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="alamat">Alamat</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="alamat" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="email">Email</label>
            </div>
            <div class="col-9">
              <input type="text" class="form-control rounded-pill" name="email" required>
            </div>
          </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batalkan</button>
        <button class="btn btn-primary rounded-pill" type="submit" name="tambah-data-pelanggan" href="#">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End modal -->

<!-- Modal Bayar -->
<div class="modal fade" id="pembayaran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="Controller.php" method="post">
          <div class="row">
            <div class="col-12 text-center">
              <h2>Total Pembayaran</h2><br>
              <hr>
              <h3><div id="total">Rp 0</div></h3>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <label for="merk">Bayar</label>
            </div>
            <div class="col-9">
              <input type="number" class="form-control rounded-pill" name="bayar" id="bayar" required>
            </div>
            <div class="col-12 text-center mt-2">
              <!-- Hints -->
              <div id="hints" class="d-flex justify-content-center flex-wrap">
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="50000">Rp 50.000</button>
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="100000">Rp 100.000</button>
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="200000">Rp 200.000</button>
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="500000">Rp 500.000</button>
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="1000000">Rp 1.000.000</button>
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="2000000">Rp 2.000.000</button>
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="5000000">Rp 5.000.000</button>
                <button type="button" class="btn btn-outline-secondary btn-sm m-1 hint-btn" data-value="10000000">Rp 10.000.000</button>
              </div>
              <!-- End hints -->
            </div>
            <div class="col-12 text-center mt-2">
              <hr>
              <h3>Kembalian</h3><br>
              <h3 id="kembalian">Rp 0</h3>
            </div>
          </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batalkan</button>
        <button class="btn btn-primary rounded-pill" type="submit" name="tambah-transaksi">Bayar Sekarang</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End modal -->

<!-- Modal edit profil-->
<div class="modal fade" id="ubah-akun-admin" tabindex="-1" aria-labelledby="ubahAkunAdminLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ubahAkunAdminLabel">Edit Akun Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="controller.php" method="POST">
          <input type="hidden" name="id_admin" value="<?php echo $_SESSION['id_admin']; ?>">
          <div class="mb-3">
            <label for="nama_admin" class="form-label">Nama Admin</label>
            <input type="text" class="form-control" id="nama_admin" name="nama_admin" value="<?php echo $_SESSION['nama_admin']; ?>" required>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" required>
          </div>
          <div class="mb-3">
            <label for="old_password" class="form-label">Password Lama</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary" name="ubah-akun-admin">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="tambah-admin-modal" tabindex="-1" aria-labelledby="tambahAdminLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahAdminLabel">Tambah Admin Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="Controller.php" method="POST">
          <div class="mb-3">
            <label for="nama_admin" class="form-label">Nama Admin</label>
            <input type="text" class="form-control" id="nama_admin_new" name="nama_admin" required>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username_new" name="username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password_new" name="password" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary" name="tambah-admin">Tambah Admin</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End modal -->
