<?php
require_once '../config/koneksi.php';
require_once '../config/crypto.php';

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $private_key = $_POST['private_key'];

    $stmt = $conn->prepare("SELECT auth_verify FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $encrypted_verify = $row['auth_verify'];

        $decrypted = decryptData($encrypted_verify, $private_key);

        if ($decrypted === 'MoneyTrackValid') {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['username'] = $username;
            
            $_SESSION['private_key'] = $private_key; 

            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Private Key salah atau data rusak!';
        }
    } else {
        $error = 'Username tidak ditemukan!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - MoneyTrack</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f4f6f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            overflow: hidden;
            background: #ffffff;
        }

        .login-header {
            background-color: #1e1e2d;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 3px solid #009ef7;
        }

        .login-title {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .login-subtitle {
            color: #a2a3b7;
            font-size: 0.9rem;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e4e6ef;
            background-color: #f5f8fa;
            transition: all 0.3s;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #009ef7;
            box-shadow: 0 0 0 0.25rem rgba(0, 158, 247, 0.1);
        }
        
        .input-group-text {
            background-color: #f5f8fa;
            border: 1px solid #e4e6ef;
            border-right: none;
            color: #a1a5b7;
            border-radius: 10px 0 0 10px;
        }

        .form-control.with-icon {
            border-left: none;
            border-radius: 0 10px 10px 0;
            padding-left: 0;
        }

        .btn-login {
            background-color: #009ef7;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #0095e8;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 158, 247, 0.25);
        }

        .back-link {
            color: #a1a5b7;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .back-link:hover {
            color: #009ef7;
            text-decoration: underline !important;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h4 class="login-title"><i class="fas fa-shield-alt text-primary me-2"></i>MoneyTrack</h4>
        <div class="login-subtitle">Otentikasi Private Key (AES-256-GCM)</div>
    </div>
    <div class="login-body">
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control with-icon" required autocomplete="off" placeholder="Masukkan username">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">Private Key</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" name="private_key" class="form-control with-icon" required autocomplete="off" placeholder="Masukkan kunci rahasia">
                </div>
                <div class="form-text mt-2"><i class="fas fa-info-circle text-primary me-1"></i> Kunci ini digunakan untuk dekripsi data.</div>
            </div>
            
            <button type="submit" class="btn btn-login w-100 text-white mt-2">
                Unlock & Login <i class="fas fa-sign-in-alt ms-2"></i>
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="../landing/index.php" class="text-decoration-none back-link">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Landing Page
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>