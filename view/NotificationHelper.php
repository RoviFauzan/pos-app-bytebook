<?php
class NotificationHelper {
    public static function getStockAlerts() {
        include dirname(__DIR__) . "/controller/Database.php";
        
        $alerts = [];
        try {
            // Get low stock items
            $query = mysqli_query($conn, "SELECT id_barang, nama_barang, stok FROM barang WHERE stok <= 5 ORDER BY stok ASC");
            
            if ($query) {
                while ($row = mysqli_fetch_assoc($query)) {
                    // Use consistent styling with data-barang.php
                    $type = $row['stok'] <= 0 ? 'danger' : 'warning';
                    $icon = $row['stok'] <= 0 ? 'mdi-alert-circle-outline' : 'mdi-alert-outline';
                    $message = $row['stok'] <= 0 ? 
                        "Stok {$row['nama_barang']} habis" : 
                        "Stok {$row['nama_barang']} tinggal {$row['stok']}";
                    
                    $alerts[] = [
                        'type' => $type,
                        'message' => $message,
                        'icon' => $icon,
                        'link' => "?u=data-barang"
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Error in getStockAlerts: " . $e->getMessage());
        }
        
        return $alerts;
    }
}
