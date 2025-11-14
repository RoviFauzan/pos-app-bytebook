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
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- End plugin css for this page -->
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
        <!-- Include proper sidebar file instead of hardcoded sidebar -->
        <?php include "sidebar.php";?>
        
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Dashboard
              </h3>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"></li>
                </ul>
              </nav>
            </div>
            
            <div class="row">
              <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                  <div class="card-body">
                    <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Total Penjualan <i class="mdi mdi-chart-line mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5"><?= "Rp. " . number_format(hitungOmsetPenjualan() ?: 0);?></h2>
                    <h6 class="card-text">Omset keseluruhan</h6>
                  </div>
                </div>
              </div>
              <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                  <div class="card-body">
                    <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pelanggan <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5"><?= countRowsPelanggan();?></h2>
                    <h6 class="card-text">Jumlah pelanggan terdaftar</h6>
                  </div>
                </div>
              </div>
              <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                  <div class="card-body">
                    <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Keuntungan <i class="mdi mdi-diamond mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5"><?= "Rp. " . number_format(hitungPendapatanBersih() ?: 0); ?></h2>
                    <h6 class="card-text">Profit bersih</h6>
                  </div>
                </div>
              </div>
            </div>

            <!-- Replace chart sections with enhanced visual implementation -->
            <div class="row">
              <div class="col-lg-7 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">
                      <i class="mdi mdi-chart-areaspline text-primary me-2"></i>
                      Penjualan Bulanan (<?= date('Y') ?>)
                    </h4>
                    <div class="chart-container" style="position: relative; height: 320px;">
                      <div style="height: 100%; width: 100%;">
                        <?php
                          // Get monthly sales data for current year
                          $salesData = getMonthlySalesData(date('Y'));
                          
                          // Find max value for scaling
                          $maxSales = max($salesData) > 0 ? max($salesData) : 1000;
                          
                          // Find month with highest sales value
                          $maxIndex = array_search(max($salesData), $salesData);
                          
                          // Month labels
                          $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                          
                          // Calculate total sales
                          $totalSales = array_sum($salesData);
                          
                          // Calculate average monthly sales for target line
                          $avgSales = $totalSales > 0 ? $totalSales / count(array_filter($salesData)) : 0;
                          $avgSales = $avgSales ?: 0; // Ensure it's not NaN
                        ?>
                        
                        <!-- CONVERTED TO LINE CHART: Replace bar chart with line chart visualization -->
                        <div style="position: relative; height: 250px; width: 100%;">
                          <!-- Grid lines for better readability -->
                          <svg viewBox="0 0 1000 250" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                            <!-- Horizontal grid lines -->
                            <?php for($i = 0; $i <= 4; $i++): 
                              $yPos = 50 * $i;
                            ?>
                              <line x1="0" y1="<?= 250 - $yPos ?>" x2="1000" y2="<?= 250 - $yPos ?>" 
                                    stroke="#e0e0e0" stroke-width="1" />
                              
                              <!-- Y-axis value labels -->
                              <text x="10" y="<?= 250 - $yPos - 5 ?>" font-size="10" fill="#6c757d">
                                <?= number_format($maxSales * ($i / 4), 0, ',', '.') ?>
                              </text>
                            <?php endfor; ?>
                            
                            <!-- Target average line -->
                            <?php if ($avgSales > 0): 
                              $targetY = 250 - (($avgSales / $maxSales) * 200);
                            ?>
                              <line x1="0" y1="<?= $targetY ?>" x2="1000" y2="<?= $targetY ?>" 
                                    stroke="#17a2b8" stroke-width="2" stroke-dasharray="5,5" />
                                    
                              <!-- Target label -->
                              <rect x="920" y="<?= $targetY - 20 ?>" width="80" height="18" rx="3" ry="3" 
                                    fill="#17a2b8" />
                              <text x="960" y="<?= $targetY - 8 ?>" font-size="10" fill="white" text-anchor="middle">
                                Target
                              </text>
                            <?php endif; ?>
                            
                            <!-- Line for sales data -->
                            <polyline 
                              points="
                                <?php 
                                  foreach ($salesData as $index => $value) {
                                    $x = ($index / 11) * 1000;
                                    $y = 250 - (($value / $maxSales) * 200);
                                    echo "$x,$y ";
                                  }
                                ?>
                              "
                              fill="none" 
                              stroke="#4B49AC" 
                              stroke-width="3"
                              stroke-linejoin="round"
                             />
                             
                             <!-- Area under the line (with gradient fill) -->
                             <linearGradient id="areaBg" x1="0%" y1="0%" x2="0%" y2="100%">
                               <stop offset="0%" stop-color="#4B49AC" stop-opacity="0.4" />
                               <stop offset="100%" stop-color="#4B49AC" stop-opacity="0.05" />
                             </linearGradient>
                             <path d="
                               M0,<?= 250 - (($salesData[0] / $maxSales) * 200) ?>
                               <?php 
                                 foreach ($salesData as $index => $value) {
                                   if ($index === 0) continue; // Skip first as it's already the starting point
                                   $x = ($index / 11) * 1000;
                                   $y = 250 - (($value / $maxSales) * 200);
                                   echo "L$x,$y ";
                                 }
                               ?>
                               L1000,250 L0,250 Z
                             " fill="url(#areaBg)" />
                             
                             <!-- Data points with hover effect -->
                             <?php foreach ($salesData as $index => $value): 
                               $x = ($index / 11) * 1000;
                               $y = 250 - (($value / $maxSales) * 200);
                               $isHighest = ($index === $maxIndex && $value > 0);
                               $pointRadius = $isHighest ? 6 : 4;
                               $pointFill = $isHighest ? '#4B49AC' : 'white';
                               $pointStroke = $isHighest ? 'white' : '#4B49AC';
                               $pointStrokeWidth = $isHighest ? 2 : 1;
                             ?>
                               <g class="data-point">
                                 <!-- Larger invisible circle for better hover target -->
                                 <circle cx="<?= $x ?>" cy="<?= $y ?>" r="10" 
                                         fill="transparent" 
                                         class="hover-target"
                                         onmouseover="showTooltip(event, '<?= $months[$index] ?>', 'Rp <?= number_format($value, 0, ',', '.') ?>')"
                                         onmouseout="hideTooltip()" />
                                         
                                 <!-- Visible point -->
                                 <circle cx="<?= $x ?>" cy="<?= $y ?>" r="<?= $pointRadius ?>" 
                                         fill="<?= $pointFill ?>" 
                                         stroke="<?= $pointStroke ?>" 
                                         stroke-width="<?= $pointStrokeWidth ?>" />
                                         
                                 <!-- Show value for highest point -->
                                 <?php if ($isHighest): ?>
                                   <text x="<?= $x ?>" y="<?= $y - 15 ?>" 
                                         font-size="11" fill="#4B49AC" text-anchor="middle" font-weight="bold">
                                     Rp <?= number_format($value, 0, ',', '.') ?>
                                   </text>
                                 <?php endif; ?>
                               </g>
                             <?php endforeach; ?>
                          </svg>
                          
                          <!-- Tooltip that follows mouse -->
                          <div id="chart-tooltip" style="position: absolute; display: none; background-color: #343a40; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; pointer-events: none; z-index: 100;">
                            <div id="tooltip-month"></div>
                            <div id="tooltip-value"></div>
                          </div>
                          
                          <!-- Month labels -->
                          <div style="display: flex; justify-content: space-between; position: absolute; bottom: -30px; left: 0; right: 0;">
                            <?php foreach($months as $index => $month): ?>
                              <div style="flex: 1; text-align: center; font-size: 12px; color: <?= $index === $maxIndex ? '#4B49AC' : '#6c757d' ?>; font-weight: <?= $index === $maxIndex ? '600' : '400' ?>;">
                                <?= $month ?>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4">
                      <div class="text-muted small">
                        <span class="text-primary">━</span> Penjualan Bulanan
                        <span class="ms-3 text-info">┄ ┄</span> Target Rata-rata
                      </div>
                      <div class="total-sales">
                        <span class="text-muted me-2">Total Penjualan:</span>
                        <strong class="text-primary">Rp <?= number_format($totalSales, 0, ',', '.') ?></strong>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-5 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">
                      <i class="mdi mdi-chart-pie text-success me-2"></i>
                      Distribusi Produk
                    </h4>
                    
                    <!-- Enhanced Product Distribution Chart with adjusted size to prevent text overlap -->
                    <div class="product-distribution-container" style="position: relative; height: 340px; display: flex; flex-direction: column; align-items: center;">
                      <?php
                        // Get product distribution data
                        $products = getDataBarang();
                        $categoryMap = [];
                        $stockMap = [];
                        $totalItems = 0;
                        $totalStock = 0;
                        
                        // Process products by category/merk
                        foreach ($products as $product) {
                          $brand = !empty($product['merk']) ? $product['merk'] : 'Lainnya';
                          $stock = intval($product['stok']);
                          
                          // Count products by category
                          if (!isset($categoryMap[$brand])) {
                            $categoryMap[$brand] = 0;
                          }
                          $categoryMap[$brand]++;
                          $totalItems++;
                          
                          // Sum stock by category
                          if (!isset($stockMap[$brand])) {
                            $stockMap[$brand] = 0;
                          }
                          $stockMap[$brand] += $stock;
                          $totalStock += $stock;
                        }
                        
                        // Sort categories by count descending
                        arsort($categoryMap);
                        
                        // Take top 5 categories for product count
                        $topCategories = array_slice($categoryMap, 0, 5, true);
                        
                        // Add "Others" category if necessary for products
                        $othersCount = 0;
                        if (count($categoryMap) > 5) {
                          $remainingCategories = array_slice($categoryMap, 5);
                          $othersCount = array_sum($remainingCategories);
                          $topCategories['Lainnya'] = $othersCount;
                        }
                        
                        // Sort categories by stock descending
                        arsort($stockMap);
                        
                        // Take top 5 categories for stock count
                        $topStockCategories = array_slice($stockMap, 0, 5, true);
                        
                        // Add "Others" category if necessary for stock
                        $othersStock = 0;
                        if (count($stockMap) > 5) {
                          $remainingStockCategories = array_slice($stockMap, 5);
                          $othersStock = array_sum($remainingStockCategories);
                          $topStockCategories['Lainnya'] = $othersStock;
                        }
                        
                        // Vibrant colors that match brand aesthetic
                        $colors = [
                          '#4B49AC', // primary (purple)
                          '#FFC100', // yellow/gold
                          '#248AFD', // blue
                          '#FF4747', // red
                          '#57B657', // green
                          '#7978E9', // light purple
                          '#F3797E'  // pink
                        ];
                      ?>
                      
                      <!-- Enhanced tabs with icons -->
                      <ul class="nav nav-pills mb-3" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active d-flex align-items-center" 
                                  id="products-tab" data-bs-toggle="pill" data-bs-target="#products" 
                                  type="button" role="tab" aria-selected="true">
                            <i class="mdi mdi-package-variant me-1"></i> Jumlah Produk
                          </button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center" 
                                  id="stock-tab" data-bs-toggle="pill" data-bs-target="#stock" 
                                  type="button" role="tab" aria-selected="false">
                            <i class="mdi mdi-package-variant-closed me-1"></i> Jumlah Stok
                          </button>
                        </li>
                      </ul>
                      
                      <div class="tab-content" id="productTabContent" style="width: 100%;">
                        <!-- Jumlah Produk Tab - ADJUSTED pie chart -->
                        <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                          <div class="product-distribution">
                            <?php if (!empty($topCategories)): ?>
                                <!-- Reduced size pie chart visualization with better space usage -->
                                <div class="d-flex flex-column align-items-center mb-2">
                                  <!-- SMALLER pie chart - reduced size to prevent text overlap -->
                                  <div class="pie-chart-container" style="width: 260px; height: 260px; position: relative; margin-bottom: 10px;">
                                    <svg viewBox="0 0 120 120" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                                      <?php
                                        $startAngle = 0;
                                        $i = 0;
                                        foreach ($topCategories as $category => $count) {
                                          $percentage = $totalItems > 0 ? ($count / $totalItems) : 0;
                                          $endAngle = $startAngle + ($percentage * 360);
                                          
                                          // SVG arc path
                                          $x1 = 60 + 50 * cos(deg2rad($startAngle));
                                          $y1 = 60 + 50 * sin(deg2rad($startAngle));
                                          $x2 = 60 + 50 * cos(deg2rad($endAngle));
                                          $y2 = 60 + 50 * sin(deg2rad($endAngle));
                                          
                                          $largeArc = ($endAngle - $startAngle > 180) ? 1 : 0;
                                          
                                          // Use standard color for better compatibility
                                          $color = $colors[$i % count($colors)];
                                          
                                          // Safe version of category name for attributes
                                          $safeCategory = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
                                          $percentageText = round($percentage * 100, 1) . '%';
                                      ?>
                                        <path d="M 60,60 L <?= $x1 ?>,<?= $y1 ?> A 50,50 0 <?= $largeArc ?>,1 <?= $x2 ?>,<?= $y2 ?> Z" 
                                              fill="<?= $color ?>" 
                                              stroke="white" 
                                              stroke-width="1" 
                                              data-category="<?= $safeCategory ?>" 
                                              data-count="<?= $count ?>"
                                              data-percentage="<?= $percentageText ?>"
                                              style="transition: all 0.3s ease;"
                                              onclick="showCategoryDetails('<?= $safeCategory ?>', <?= $count ?>, '<?= $percentageText ?>')"
                                              onmouseover="this.style.opacity=0.8; this.style.cursor='pointer';"
                                              onmouseout="this.style.opacity=1;">
                                        </path>
                                      <?php
                                        $startAngle = $endAngle;
                                        $i++;
                                        }
                                      ?>
                                    </svg>
                                    
                                    <!-- Center circle with total count - smaller size -->
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                      <div style="width: 100px; height: 100px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; flex-direction: column; box-shadow: 0 0 15px rgba(0,0,0,0.1);">
                                        <span style="font-size: 40px; font-weight: bold; color: #4B49AC; line-height: 1.1;"><?= $totalItems ?></span>
                                        <span style="font-size: 14px; color: #666;">produk</span>
                                      </div>
                                    </div>
                                  </div>
                                  
                                  <!-- More compact legend with abbreviated text -->
                                  <div class="row mt-2" style="max-width: 100%;">
                                    <?php
                                      $i = 0;
                                      foreach ($topCategories as $category => $count):
                                        $percentage = $totalItems > 0 ? round(($count / $totalItems) * 100, 1) : 0;
                                        $color = $colors[$i % count($colors)];
                                        
                                        // Abbreviate long brand names
                                        $displayCategory = strlen($category) > 12 ? 
                                            mb_substr($category, 0, 10) . '..' : 
                                            $category;
                                        $i++;
                                    ?>
                                      <div class="col-6 mb-1">
                                        <div class="d-flex align-items-center">
                                          <div style="min-width: 10px; height: 10px; background-color: <?= $color ?>; margin-right: 6px; border-radius: 2px;"></div>
                                          <div class="d-flex justify-content-between align-items-center w-100">
                                            <span class="text-truncate" style="max-width: 80px; font-size: 12px;" title="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($displayCategory) ?></span>
                                            <span class="ms-1 text-muted" style="font-size: 12px;"><?= $percentage ?>%</span>
                                          </div>
                                        </div>
                                      </div>
                                    <?php endforeach; ?>
                                  </div>
                                </div>

                                <!-- Simple popup for category details without fixed placement -->
                                <div id="category-details" style="display: none; background-color: white; padding: 10px 15px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin: 0 auto; width: fit-content; text-align: center;">
                                  <h6 id="selected-category" style="margin-bottom: 5px; color: #4B49AC; font-size: 14px;"></h6>
                                  <div class="d-flex justify-content-between" style="gap: 10px; font-size: 13px;">
                                    <span>Jumlah:</span>
                                    <span id="selected-count" class="fw-bold"></span>
                                  </div>
                                  <div class="d-flex justify-content-between" style="gap: 10px; font-size: 13px;">
                                    <span>Persentase:</span>
                                    <span id="selected-percentage" class="fw-bold"></span>
                                  </div>
                                </div>
                              <?php else: ?>
                              <div class="text-center text-muted py-5">
                                <i class="mdi mdi-package-variant-closed" style="font-size: 48px;"></i>
                                <p class="mt-2">Tidak ada data produk</p>
                              </div>
                            <?php endif; ?>
                          </div>
                        </div>
                        
                        <!-- Jumlah Stok Tab - More compact layout -->
                        <div class="tab-pane fade" id="stock" role="tabpanel" aria-labelledby="stock-tab">
                          <div class="stock-distribution d-flex flex-column align-items-center">
                            <?php if (!empty($topStockCategories)): ?>
                              <!-- Visual stacked bar representation -->
                              <div class="stacked-bar-chart mb-3" style="height: 40px; background: #f5f5f5; border-radius: 4px; overflow: hidden; display: flex; width: 100%;">
                                <?php
                                  $i = 0;
                                  foreach ($topStockCategories as $category => $stock):
                                    $percentage = $totalStock > 0 ? ($stock / $totalStock) * 100 : 0;
                                    $color = $colors[$i % count($colors)];
                                    $i++;
                                ?>
                                  <div style="width: <?= $percentage ?>%; height: 100%; background-color: <?= $color ?>;" 
                                       title="<?= htmlspecialchars($category) ?>: <?= $stock ?> unit (<?= round($percentage, 1) ?>%)">
                                  </div>
                                <?php endforeach; ?>
                              </div>
                              
                              <!-- More compact stock distribution view -->
                              <div style="width: 100%;">
                                <?php
                                  $i = 0;
                                  foreach ($topStockCategories as $category => $stock):
                                    $percentage = $totalStock > 0 ? round(($stock / $totalStock) * 100, 1) : 0;
                                    $color = $colors[$i % count($colors)];
                                    
                                    // Abbreviate long brand names
                                    $displayCategory = strlen($category) > 15 ? 
                                        mb_substr($category, 0, 13) . '..' : 
                                        $category;
                                    $i++;
                                ?>
                                  <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 13px;">
                                      <span class="fw-bold" title="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($displayCategory) ?></span>
                                      <span><?= $stock ?> unit (<?= $percentage ?>%)</span>
                                    </div>
                                    <div class="progress" style="height: 12px;">
                                      <div class="progress-bar" role="progressbar" 
                                          style="width: <?= $percentage ?>%; background-color: <?= $color ?>;"
                                          aria-valuenow="<?= $percentage ?>" 
                                          aria-valuemin="0" 
                                          aria-valuemax="100">
                                      </div>
                                    </div>
                                  </div>
                                <?php endforeach; ?>
                              </div>
                              
                              <!-- Total stock summary - more compact -->
                              <div class="text-center mt-2 pt-1 border-top" style="width: 100%;">
                                <div class="text-muted" style="font-size: 13px;">
                                  Total Stok: <strong class="text-success"><?= $totalStock ?> unit</strong>
                                </div>
                              </div>
                              
                            <?php else: ?>
                              <div class="text-center text-muted py-5">
                                <i class="mdi mdi-package-variant-closed" style="font-size: 48px;"></i>
                                <p class="mt-2">Tidak ada data stok</p>
                              </div>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                      <h4 class="card-title mb-0">Transaksi Terbaru</h4>
                      <?php if (!isKasir()): // Hide "Lihat Semua" button for Kasir users ?>
                      <a href="<?= $_SERVER['PHP_SELF'] . '?u=data-transaksi'; ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                      <?php endif; ?>
                    </div>
                    
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $recentTransactions = getRecentTransactions(5);
                          if (!empty($recentTransactions)) {
                            foreach ($recentTransactions as $transaction) {
                              $date = date("d M Y", strtotime($transaction['tanggal']));
                              echo "<tr>";
                              echo "<td>{$transaction['id_transaksi']}</td>";
                              echo "<td>{$date}</td>";
                              echo "<td>{$transaction['nama_pelanggan']}</td>";
                              echo "<td class='text-end'>Rp " . number_format($transaction['total_pembelian']) . "</td>";
                              echo "<td><a href='{$_SERVER['PHP_SELF']}?u=print-nota&id={$transaction['id_transaksi']}' class='btn btn-sm btn-outline-primary'>Cetak</a></td>";
                              echo "</tr>";
                            }
                          } else {
                            echo "<tr><td colspan='5' class='text-center'>Belum ada transaksi</td></tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Statistik Inventori</h4>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="bg-light p-4 rounded text-center">
                          <h3><?= countRowsBarang() ?></h3>
                          <p class="text-muted">Total Produk</p>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="bg-light p-4 rounded text-center">
                          <h3><?= countTransactions() ?></h3>
                          <p class="text-muted">Total Transaksi</p>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="bg-light p-4 rounded text-center">
                          <h3>
                            <?php
                              $totalStock = 0;
                              $products = getDataBarang();
                              foreach ($products as $product) {
                                $totalStock += $product['stok'];
                              }
                              echo $totalStock;
                            ?>
                          </h3>
                          <p class="text-muted">Total Stok</p>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="bg-light p-4 rounded text-center">
                          <h3>
                            <?php
                              $lowStockCount = 0;
                              foreach ($products as $product) {
                                if ($product['stok'] <= 5) {
                                  $lowStockCount++;
                                }
                              }
                              echo $lowStockCount;
                            ?>
                          </h3>
                          <p class="text-muted">Stok Menipis</p>
                        </div>
                      </div>
                    </div>
                    
                    <?php if ($lowStockCount > 0): ?>
                    <div class="alert alert-warning mt-4 mb-0">
                      <h6><i class="mdi mdi-alert-circle"></i> Peringatan Stok Menipis</h6>
                      <ul class="mb-0 mt-2">
                        <?php
                          $count = 0;
                          foreach ($products as $product) {
                            if ($product['stok'] <= 5 && $count < 3) {
                              echo "<li><b>{$product['nama_barang']}</b> - Sisa stok: {$product['stok']}</li>";
                              $count++;
                            }
                          }
                          
                          $remaining = $lowStockCount - 3;
                          if ($remaining > 0) {
                            echo "<li>dan {$remaining} produk lainnya...</li>";
                          }
                        ?>
                      </ul>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <?php include "footer.php";?>
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
    <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/settings.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page - improved showCategoryDetails function -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard initialized with enhanced charts');
        
        // Initialize tooltips
        try {
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
          tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
          });
        } catch(e) {
          console.warn('Tooltip initialization error:', e);
        }
        
        // Format currency helper function - safe implementation that only formats when called directly
        window.formatRupiah = function(value) {
          try {
            // Handle null/undefined values
            if (value === null || value === undefined) return 'Rp 0';
            
            // Convert to number if not already
            let numValue = (typeof value === 'number') ? value : Number(value);
            
            // Return formatted number with Rp prefix
            return 'Rp ' + numValue.toLocaleString('id-ID');
          } catch(e) {
            console.warn('formatRupiah error:', e);
            return 'Rp ' + value;
          }
        };
        
        // Tooltip functions for line chart - now uses pre-formatted values
        window.showTooltip = function(event, month, value) {
          const tooltip = document.getElementById('chart-tooltip');
          if (!tooltip) return;
          
          tooltip.style.display = 'block';
          
          // Position tooltip relative to mouse pointer
          const x = event.clientX;
          const y = event.clientY;
          
          // Keep tooltip within viewport
          tooltip.style.left = (x - 50) + 'px';
          tooltip.style.top = (y - 70) + 'px';
          
          // Populate tooltip content
          tooltip.innerHTML = `
            <div class="fw-bold">${month}</div>
            <div>${value}</div>
          `;
        };
        
        window.hideTooltip = function() {
          const tooltip = document.getElementById('chart-tooltip');
          if (tooltip) {
            tooltip.style.display = 'none';
          }
        };
        
        // Show category details - improved to handle layout better
        window.showCategoryDetails = function(category, count, percentage) {
          const detailsDiv = document.getElementById('category-details');
          const categoryEl = document.getElementById('selected-category');
          const countEl = document.getElementById('selected-count');
          const percentageEl = document.getElementById('selected-percentage');
          
          if (!detailsDiv || !categoryEl || !countEl || !percentageEl) return;
          
          categoryEl.textContent = category;
          countEl.textContent = count;
          percentageEl.textContent = percentage;
          
          // Clear any previous animation/timeout
          if (window.categoryDetailsTimeout) {
            clearTimeout(window.categoryDetailsTimeout);
          }
          
          // Show with fade-in effect
          detailsDiv.style.opacity = 0;
          detailsDiv.style.display = 'block';
          
          setTimeout(() => {
            detailsDiv.style.opacity = 1;
            detailsDiv.style.transition = 'opacity 0.3s ease';
          }, 10);
          
          // Auto-hide after 3 seconds
          window.categoryDetailsTimeout = setTimeout(() => {
            detailsDiv.style.opacity = 0;
            setTimeout(() => {
              detailsDiv.style.display = 'none';
            }, 300);
          }, 3000);
        };
      });
    </script>
    <!-- End custom js for this page -->
  </body>
</html>
