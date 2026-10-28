<?php
/**
 * Database connection (PDO) — Postgres on Render, MySQL locally
 */
require_once __DIR__ . '/config.php';

// Detect if we're on Postgres (Render) or MySQL
$isPostgres = str_contains(DB_HOST, 'render.com') || str_contains(DB_HOST, 'dpg-') || getenv('DATABASE_URL');

try {
    if ($isPostgres) {
        $dsn = "pgsql:host=" . DB_HOST . ";port=5432;dbname=" . DB_NAME;
    } else {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    }

    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}
