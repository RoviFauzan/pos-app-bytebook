<?php
class ExportHelper {
    /**
     * Export transactions to Excel file
     * 
     * @param array $transactions Array of transaction data
     */
    public static function exportToExcel($transactions) {
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="data_transaksi_'.date('Ymd_His').'.xls"');
        header('Cache-Control: max-age=0');
        
        // Start output buffering
        ob_start();
        
        // Add HTML for Excel file
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<title>Data Transaksi</title>';
        echo '<style>';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th, td { border: 1px solid #000; padding: 5px; }';
        echo 'th { background-color: #4B49AC; color: white; }';
        echo '.text-right { text-align: right; }';
        echo '.text-center { text-align: center; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        
        // Add title and export date
        echo '<h2>Data Transaksi BYTEBOOK</h2>';
        echo '<p>Tanggal Export: ' . date('d/m/Y H:i:s') . '</p>';
        
        // Create the table
        echo '<table border="1">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID Transaksi</th>';
        echo '<th>Tanggal</th>';
        echo '<th>Pelanggan</th>';
        echo '<th>Total Pembelian</th>';
        echo '<th>Bayar</th>';
        echo '<th>Kembalian</th>';
        echo '<th>Keterangan</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        // Add transaction data
        $totalPembelian = 0;
        foreach ($transactions as $transaksi) {
            echo '<tr>';
            echo '<td class="text-center">' . $transaksi['id_transaksi'] . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($transaksi['tanggal'])) . '</td>';
            echo '<td>' . $transaksi['nama_pelanggan'] . '</td>';
            echo '<td class="text-right">Rp ' . number_format($transaksi['total_pembelian'], 0, ',', '.') . '</td>';
            echo '<td class="text-right">Rp ' . number_format($transaksi['bayar'], 0, ',', '.') . '</td>';
            echo '<td class="text-right">Rp ' . number_format($transaksi['kembalian'], 0, ',', '.') . '</td>';
            echo '<td>' . $transaksi['keterangan'] . '</td>';
            echo '</tr>';
            $totalPembelian += $transaksi['total_pembelian'];
        }
        
        echo '</tbody>';
        echo '<tfoot>';
        echo '<tr>';
        echo '<th colspan="3" class="text-right">Total Keseluruhan</th>';
        echo '<th class="text-right">Rp ' . number_format($totalPembelian, 0, ',', '.') . '</th>';
        echo '<th colspan="3"></th>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';
        
        // Add footer
        echo '<p style="margin-top:20px; font-size:12px; text-align:center;">BYTEBOOK - ' . date('Y') . '</p>';
        echo '</body>';
        echo '</html>';
        
        // End and flush buffer
        echo ob_get_clean();
        exit;
    }
}
