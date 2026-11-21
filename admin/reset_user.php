<?php
// admin/reset_user.php
require_once '../config/koneksi.php';
require_once '../config/crypto.php';

$username = 'admin';
$private_key = 'KunciRahasia123';
$teks_validasi = 'MoneyTrackValid';

// Enkripsi teks validasi pakai private key default
$encrypted_verify = encryptData($teks_validasi, $private_key);

// Hapus user lama kalau ada
$conn->query("DELETE FROM users WHERE username = 'admin'");

// Masukkan user admin baru dengan data enkripsi default
$stmt = $conn->prepare("INSERT INTO users (username, auth_verify) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $encrypted_verify);
$status = $stmt->execute();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset User - MoneyTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f4f6f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
        }
        .card-custom {
            width: 100%;
            max-width: 440px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
            background: #ffffff;
        }
        .card-header-custom {
            background-color: #1e1e2d;
            padding: 25px 20px;
            text-align: center;
            border-bottom: 3px solid #009ef7;
        }
    </style>
</head>
<body>
<div class="card-custom">
    <div class="card-header-custom">
        <h5 class="text-white fw-bold mb-1"><i class="fas fa-shield-alt text-primary me-2"></i>MoneyTrack</h5>
        <div class="text-muted small">Reset Kredensial Default</div>
    </div>
    <div class="p-4">
        <?php if ($status): ?>
            <div class="alert alert-success border-0 small mb-4">
                <i class="fas fa-check-circle me-1"></i> User berhasil di-reset ke nilai default!
            </div>
            <ul class="list-group list-group-flush mb-4 small">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Username:</span>
                    <span class="fw-bold">admin</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Private Key Default:</span>
                    <span class="fw-bold text-primary font-monospace">KunciRahasia123</span>
                </li>
            </ul>
            <div class="d-grid gap-2">
                <a href="login.php" class="btn btn-primary py-2 fw-semibold" style="border-radius: 10px;">
                    <i class="fas fa-sign-in-alt me-2"></i>Ke Halaman Login
                </a>
                <a href="login.php?setup=1" class="btn btn-outline-secondary py-2 fw-semibold" style="border-radius: 10px;">
                    <i class="fas fa-key me-2"></i>Atur Kunci Sendiri (Setup)
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-danger border-0 small mb-4">
                <i class="fas fa-times-circle me-1"></i> Gagal mereset: <?= htmlspecialchars($conn->error) ?>
            </div>
            <a href="login.php?setup=1" class="btn btn-primary w-100 py-2">Ke Halaman Setup</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
