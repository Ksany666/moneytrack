<?php
// admin/reset_user.php
require_once '../config/koneksi.php';
require_once '../config/crypto.php';

$username = 'admin';
$private_key = 'KunciRahasia123'; // Ini Private Key yang bakal lu pake buat login
$teks_validasi = 'MoneyTrackValid';

// Enkripsi teks validasi pakai private key
$encrypted_verify = encryptData($teks_validasi, $private_key);

// Hapus user admin lama kalau ada
$conn->query("DELETE FROM users WHERE username = 'admin'");

// Masukkan user admin baru dengan data enkripsi yang fresh
$stmt = $conn->prepare("INSERT INTO users (username, auth_verify) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $encrypted_verify);

if ($stmt->execute()) {
    echo "User berhasil di-reset!<br>";
    echo "Username: admin<br>";
    echo "Private Key: KunciRahasia123<br>";
    echo "<a href='login.php'>Kembali ke Login</a>";
} else {
    echo "Gagal: " . $conn->error;
}
?>