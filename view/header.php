<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <!-- SweetAlert2 CSS and JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
        <script src="../assets/js/alert-helper.js"></script>
        <style>
            .swal2-popup {
                font-family: 'Roboto', sans-serif;
                border-radius: 10px;
            }
            .swal2-title {
                font-size: 1.5em;
            }
            .swal2-html-container {
                text-align: left;
            }
            .stock-item {
                background-color: #f8d7da;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 10px;
            }
            .count-symbol {
                position: absolute;
                top: 10px;
                right: 10px;
                width: 10px;
                height: 10px;
                border-radius: 50%;
            }

            .preview-icon {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
            }

            .dropdown-menu {
                max-height: 400px;
                overflow-y: auto;
            }

            .notification-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                padding: 3px 6px;
                border-radius: 50%;
                font-size: 10px;
            }
        </style>
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo d-flex align-items-center" href="<?= $_SERVER['PHP_SELF'] . '?u=home'; ?>" style="justify-content: flex-start; text-align: left;">
          <img src="../assets/images/logo.png" alt="logo" style="width: 50px; height: 50px; margin: 10px; justify-content: flex-start; text-align: left; border-radius: 50%; ">
          <div class="d-flex flex-column">
            <h5 style="margin: 0; font-size: 1.3rem;">BYTEBOOK</h5>
          </div>
        </a>
          <a class="navbar-brand brand-logo-mini" href="<?= $_SERVER['PHP_SELF'] . '?u=home'; ?>">
            <h3></h3>
          </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="search-field d-none d-md-block">
            <!-- <form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control bg-transparent border-0" placeholder="Cari di sini...">
              </div>
            </form> -->
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
                <?php
                require_once "NotificationHelper.php";
                try {
                    $notifications = NotificationHelper::getStockAlerts();
                } catch (Exception $e) {
                    $notifications = [];
                }
                ?>
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <?php if (!empty($notifications)): ?>
                        <span class="count-symbol bg-danger"></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0 bg-primary text-white py-4">Notifikasi</h6>
                    <div class="dropdown-divider"></div>
                    <?php if (empty($notifications)): ?>
                        <p class="p-3 mb-0 text-center">Tidak ada notifikasi</p>
                    <?php else: ?>
                        <?php foreach($notifications as $notif): ?>
                            <a class="dropdown-item preview-item" href="<?= $notif['link'] ?>">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-<?= $notif['type'] ?>">
                                        <i class="mdi <?= $notif['icon'] ?> text-white"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1"><?= htmlspecialchars($notif['message']) ?></h6>
                                    <p class="text-gray ellipsis mb-0">Klik untuk melihat detail</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </li>
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link " id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="../assets/images/faces-clipart/pic-2.png" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black"><?= $_SESSION['nama_admin'];?></p>
                </div>
              </a>
            </li>
            <li class="nav-item nav-logout d-none d-lg-block">
              <a class="nav-link" href="<?= $_SERVER['PHP_SELF'] . '?u=logout'; ?>">
                <i class="mdi mdi-power"></i> Signout
              </a>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>