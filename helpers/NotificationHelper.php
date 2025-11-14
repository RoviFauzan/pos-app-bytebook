<?php
class NotificationHelper {
    public static function getStockAlerts() {
        include dirname(__DIR__) . "/controller/Database.php";
        
        $alerts = [];
        
        try {
            // Check for low stock items
            $query = mysqli_query($conn, "SELECT id_barang, nama_barang, stok FROM barang WHERE stok <= 5");
            
            if (!$query) {
                throw new Exception(mysqli_error($conn));
            }
            
            while ($row = mysqli_fetch_assoc($query)) {
                $type = $row['stok'] <= 0 ? 'danger' : 'warning';
                $message = $row['stok'] <= 0 ? 
                    "Stok {$row['nama_barang']} telah habis!" : 
                    "Stok {$row['nama_barang']} tinggal {$row['stok']} unit";
                
                $alerts[] = [
                    'type' => $type,
                    'message' => $message,
                    'icon' => $type === 'danger' ? 'mdi-alert-circle' : 'mdi-alert',
                    'link' => "?u=data-barang"
                ];
            }
        } catch (Exception $e) {
            // Return empty array if there's an error
            error_log("Error in getStockAlerts: " . $e->getMessage());
            return [];
        }
        
        return $alerts;
    }
}
