<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Ensure transaction ID is valid and never zero
    $displayId = max(1, intval($transaksi['id_transaksi']));
    ?>
    <title>Nota Transaksi #TRX-<?= str_pad($displayId + 1000, 4, '0', STR_PAD_LEFT) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            border-radius: 10px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eeeeee;
            padding-bottom: 20px;
        }
        .invoice-header img {
            max-height: 50px;
            margin-bottom: 10px;
        }
        .invoice-header h2 {
            margin-bottom: 5px;
            color: #5d5d5d;
        }
        .customer-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .customer-info div {
            flex: 1;
        }
        .invoice-id {
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .invoice-meta {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eeeeee;
        }
        th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .total-section table {
            width: 300px;
        }
        .btn-print {
            background-color: #4B49AC;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 20px;
        }
        .btn-print:hover {
            background-color: #3c3a89;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 20px;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        @media print {
            .actions {
                display: none;
            }
            body {
                padding: 0;
                background-color: white;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h2>BYTEBOOK</h2>
            <p>Nota Transaksi</p>
        </div>
        
        <div class="customer-info">
            <div>
                <div class="invoice-id">Nota #TRX-<?= str_pad($displayId + 1000, 4, '0', STR_PAD_LEFT) ?></div>
                <div class="invoice-meta">Tanggal: <?= date('d-m-Y H:i', strtotime($transaksi['tanggal'])) ?></div>
            </div>
            <div>
                <div><strong>Pelanggan:</strong> <?= $transaksi['nama_pelanggan'] ?></div>
                <?php if (!empty($transaksi['no_hp'])): ?>
                <div><strong>No HP:</strong> <?= $transaksi['no_hp'] ?></div>
                <?php endif; ?>
                <?php if (!empty($transaksi['alamat'])): ?>
                <div><strong>Alamat:</strong> <?= $transaksi['alamat'] ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                // Filter to only show items from this specific transaction ID
                $currentTransactionItems = array_filter($detailTransaksi, function($item) use ($transaksi) {
                    return $item['id_transaksi'] == $transaksi['id_transaksi'];
                });
                
                foreach ($currentTransactionItems as $item): 
                    $subtotal = $item['qty'] * $item['harga_jual'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    <td>Rp <?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td class="text-right">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="total-section">
            <table>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right">Rp <?= number_format($transaksi['total_pembelian'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td><strong>Dibayar</strong></td>
                    <td class="text-right">Rp <?= number_format($transaksi['bayar'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td><strong>Kembalian</strong></td>
                    <td class="text-right">Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?></td>
                </tr>
            </table>
        </div>
        
        <?php if (!empty($transaksi['keterangan'])): ?>
        <div style="margin-top: 30px;">
            <strong>Keterangan:</strong>
            <p><?= nl2br(htmlspecialchars($transaksi['keterangan'])) ?></p>
        </div>
        <?php endif; ?>
        
        <div class="actions">
            <a href="Controller.php?u=data-transaksi" class="btn-back">Kembali ke Data Transaksi</a>
            <button onclick="window.print()" class="btn-print">
                <i class="mdi mdi-printer"></i> Cetak Nota
            </button>
        </div>
    </div>
    
    <script>
        // Auto print if URL has print parameter
        if (window.location.search.includes('print=true')) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            });
        }
    </script>
</body>
</html>
