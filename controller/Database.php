<?php
$host   = getenv('SUPABASE_DB_HOST') ?: 'YOUR-PROJECT.supabase.co';
$dbname = getenv('SUPABASE_DB_NAME') ?: 'postgres';
$user   = getenv('SUPABASE_DB_USER') ?: 'postgres';
$pass   = getenv('SUPABASE_DB_PASSWORD') ?: '';
$port   = getenv('SUPABASE_DB_PORT') ?: '5432';
$ssl    = getenv('SUPABASE_DB_SSLMODE') ?: 'require';

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode={$ssl}";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi DB gagal: " . $e->getMessage());
}

function db(): ?PDO {
    global $pdo;
    return $pdo;
}
?>