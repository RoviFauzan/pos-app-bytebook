<?php
// Supabase Postgres connection via PDO (active)
// Uses environment variables:
// SUPABASE_DB_HOST, SUPABASE_DB_PORT, SUPABASE_DB_NAME, SUPABASE_DB_USER, SUPABASE_DB_PASSWORD, SUPABASE_DB_SSLMODE
$host   = getenv('SUPABASE_DB_HOST') ?: 'YOUR-PROJECT-HOST.supabase.co';
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
    trigger_error("DB connection failed: " . $e->getMessage(), E_USER_ERROR);
}

function db(): ?PDO {
    return isset($pdo) ? $pdo : null;
}
?>