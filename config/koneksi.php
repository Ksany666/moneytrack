<?php
// config/koneksi.php

// Jalankan session di sini karena hampir semua halaman butuh session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi sederhana untuk membaca file .env
function loadEnv($path) {
    if (!file_exists($path)) return false;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Abaikan komentar
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Load .env dari root folder (naik 1 folder dari config)
loadEnv(__DIR__ . '/../.env');

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? 'db_moneytrack';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}
?>