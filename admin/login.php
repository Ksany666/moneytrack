<?php
require_once '../config/koneksi.php';
require_once '../config/crypto.php';

$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

// Ambil data user dari database
$checkUsers = $conn->query("SELECT id, username, auth_verify FROM users");
$userList = [];
if ($checkUsers && $checkUsers->num_rows > 0) {
    while ($u = $checkUsers->fetch_assoc()) {
        $userList[] = $u;
    }
}
$userCount = count($userList);
$existingUser = $userList[0] ?? null;

// Jika user sudah login, arahkan langsung ke dashboard
if ($is_logged_in) {
    header("Location: dashboard.php");
    exit;
}

$error_login = '';
$error_setup = '';
$success_setup = false;
$saved_key = '';
$saved_username = '';

// Tentukan tab aktif awal
$activeTab = (isset($_GET['setup']) || $userCount === 0) ? 'setup' : 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_type = $_POST['action_type'] ?? 'login';

    if ($action_type === 'setup') {
        $activeTab = 'setup';

        if (!empty($_SESSION['is_encrypted_mode'])) {
            $error_setup = 'Akses ditolak: Master key terkunci.';
        } else {
            $username = trim($_POST['username'] ?? '');
            if (empty($username)) {
                $username = $existingUser['username'] ?? 'Admin';
            }

            $private_key = trim($_POST['private_key'] ?? '');
            $confirm_key = trim($_POST['confirm_private_key'] ?? '');
            $reset_transactions = isset($_POST['reset_transactions']) && $_POST['reset_transactions'] == '1';

            if (empty($private_key)) {
                $error_setup = 'Private Key tidak boleh kosong!';
            } elseif (strlen($private_key) < 6) {
                $error_setup = 'Private Key minimal 6 karakter!';
            } elseif ($private_key !== $confirm_key) {
                $error_setup = 'Konfirmasi Private Key tidak cocok!';
            } else {
                $encrypted_verify = encryptData('MoneyTrackValid', $private_key);

                if (!$encrypted_verify) {
                    $error_setup = 'Gagal mengenkripsi Private Key. Pastikan OpenSSL aktif!';
                } else {
                    if ($reset_transactions) {
                        $conn->query("TRUNCATE TABLE transaksi");
                        $conn->query("TRUNCATE TABLE kategori");
                    }

                    if ($existingUser) {
                        $stmt = $conn->prepare("UPDATE users SET username = ?, auth_verify = ? WHERE id = ?");
                        $stmt->bind_param("ssi", $username, $encrypted_verify, $existingUser['id']);
                    } else {
                        $stmt = $conn->prepare("INSERT INTO users (username, auth_verify) VALUES (?, ?)");
                        $stmt->bind_param("ss", $username, $encrypted_verify);
                    }

                    if ($stmt->execute()) {
                        $success_setup = true;
                        $saved_key = $private_key;
                        $saved_username = $username;

                        // Perbarui data user aktif
                        $existingUser = ['id' => $existingUser['id'] ?? $conn->insert_id, 'username' => $username, 'auth_verify' => $encrypted_verify];
                        $userList = [$existingUser];
                        $userCount = 1;

                        if ($is_logged_in) {
                            $_SESSION['username'] = $username;
                            $_SESSION['private_key'] = $private_key;
                            $_SESSION['is_encrypted_mode'] = false;
                        }
                    } else {
                        $error_setup = 'Gagal menyimpan ke database: ' . $conn->error;
                    }
                }
            }
        }
    } else {
        // PROSES LOGIN
        $activeTab = 'login';
        $private_key = trim($_POST['private_key'] ?? '');

        if (empty($private_key)) {
            $error_login = 'Silakan masukkan Password (Private Key)!';
        } else {
            $authenticated = false;
            $first_user = $userList[0] ?? null;

            foreach ($userList as $row) {
                $decrypted = decryptData($row['auth_verify'], $private_key);

                if ($decrypted === 'MoneyTrackValid') {
                    $authenticated = true;
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['is_encrypted_mode'] = false;
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'] ?: 'Admin';
                    $_SESSION['private_key'] = $private_key;

                    header("Location: dashboard.php");
                    exit;
                }
            }

            if (!$authenticated) {
                // Password salah -> Tetap masuk dashboard dalam mode enkripsi honeypot
                $_SESSION['is_logged_in'] = true;
                $_SESSION['is_encrypted_mode'] = true;
                $_SESSION['user_id'] = $first_user ? $first_user['id'] : 1;
                $_SESSION['username'] = $first_user ? ($first_user['username'] ?: 'Admin') : 'Admin';
                $_SESSION['private_key'] = $private_key;

                header("Location: dashboard.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Setup Key - MoneyTrack</title>
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
            padding: 30px 15px;
        }
        
        .auth-card {
            width: 100%;
            max-width: 480px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            overflow: hidden;
            background: #ffffff;
            transition: all 0.3s;
        }

        .auth-header {
            background-color: #1e1e2d;
            padding: 28px 24px 22px 24px;
            text-align: center;
            border-bottom: 3px solid #009ef7;
        }

        .auth-title {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .auth-subtitle {
            color: #a2a3b7;
            font-size: 0.88rem;
        }

        .auth-body {
            padding: 30px 28px;
        }

        /* Pill Tabs Modern */
        .auth-pills {
            background-color: #f1f4f8;
            border-radius: 14px;
            padding: 5px;
            margin-bottom: 25px;
        }

        .auth-pills .nav-link {
            color: #5e6278;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 10px 16px;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .auth-pills .nav-link:hover {
            color: #009ef7;
        }

        .auth-pills .nav-link.active {
            background-color: #ffffff;
            color: #009ef7;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
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
            border-right: none;
            border-radius: 0;
            padding-left: 0;
        }

        .btn-toggle-eye {
            background-color: #f5f8fa;
            border: 1px solid #e4e6ef;
            border-left: none;
            color: #a1a5b7;
            border-radius: 0 10px 10px 0;
            transition: 0.2s;
        }

        .btn-toggle-eye:hover {
            color: #009ef7;
            background-color: #f1f4f8;
        }

        .btn-primary-action {
            background-color: #009ef7;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-primary-action:hover {
            background-color: #0095e8;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 158, 247, 0.25);
        }

        .key-display-box {
            background-color: #f8f9fa;
            border: 2px dashed #009ef7;
            border-radius: 12px;
            padding: 14px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.05rem;
            font-weight: bold;
            color: #1e1e2d;
            word-break: break-all;
            text-align: center;
        }

        .back-link {
            color: #a1a5b7;
            font-size: 0.88rem;
            transition: 0.3s;
        }

        .back-link:hover {
            color: #009ef7;
            text-decoration: underline !important;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <h4 class="auth-title"><i class="fas fa-shield-alt text-primary me-2"></i>MoneyTrack</h4>
        <div class="auth-subtitle">Sistem Keuangan Mandiri AES-256-GCM</div>
    </div>
    
    <div class="auth-body">

        <!-- Tab Switcher (Login vs Setup) -->
        <ul class="nav nav-pills nav-fill auth-pills" id="authTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $activeTab === 'login' ? 'active' : '' ?>" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" aria-controls="pills-login" aria-selected="<?= $activeTab === 'login' ? 'true' : 'false' ?>">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $activeTab === 'setup' ? 'active' : '' ?>" id="pills-setup-tab" data-bs-toggle="pill" data-bs-target="#pills-setup" type="button" role="tab" aria-controls="pills-setup" aria-selected="<?= $activeTab === 'setup' ? 'true' : 'false' ?>">
                    <i class="fas fa-key me-2"></i>Setup Key
                </button>
            </li>
        </ul>

        <div class="tab-content" id="authTabContent">

            <!-- ================= TAB 1: LOGIN ================= -->
            <div class="tab-pane fade <?= $activeTab === 'login' ? 'show active' : '' ?>" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab">
                
                <?php if ($error_login): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($error_login) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($userCount == 0): ?>
                    <div class="alert alert-warning border-0 shadow-sm mb-4 small">
                        <i class="fas fa-info-circle me-1"></i> Belum ada akun master yang dibuat. Silakan klik tab <b>Setup Key</b> di atas untuk membuat Private Key pertama kali.
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="action_type" value="login">
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold text-dark mb-0">Password (Private Key)</label>
                            <a href="javascript:void(0)" onclick="switchToSetupTab()" class="text-decoration-none small text-primary fw-semibold">
                                Atur / Ubah Key?
                            </a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" name="private_key" id="loginKeyInput" class="form-control with-icon" required autocomplete="current-password" placeholder="Masukkan Private Key">
                            <button class="btn btn-toggle-eye" type="button" onclick="togglePasswordVisibility('loginKeyInput', 'loginEyeIcon')" title="Lihat / Sembunyikan Kunci">
                                <i class="fas fa-eye" id="loginEyeIcon"></i>
                            </button>
                        </div>
                        <div class="form-text mt-2 small text-muted"><i class="fas fa-lock text-success me-1"></i> Kunci ini digunakan langsung untuk mendekripsi data transaksi Anda.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-action w-100 text-white mt-1">
                        Unlock & Masuk <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <span class="text-muted small">Belum punya / lupa Private Key?</span>
                    <a href="javascript:void(0)" onclick="switchToSetupTab()" class="text-decoration-none small text-primary fw-bold ms-1">
                        Buka Setup Key
                    </a>
                </div>
            </div>

            <!-- ================= TAB 2: SETUP PRIVATE KEY ================= -->
            <div class="tab-pane fade <?= $activeTab === 'setup' ? 'show active' : '' ?>" id="pills-setup" role="tabpanel" aria-labelledby="pills-setup-tab">
                
                <?php if ($error_setup): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($error_setup) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($success_setup): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-3">
                        <h6 class="fw-bold mb-1"><i class="fas fa-check-circle me-2"></i>Private Key Berhasil Disimpan!</h6>
                        <p class="mb-0 small">Kunci otentikasi AES-256-GCM Anda telah aktif. Pastikan Anda menyimpan atau mencatat kunci di bawah ini sebelum melanjutkan.</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">PRIVATE KEY ANDA:</label>
                        <div class="key-display-box" id="savedKeyText"><?= htmlspecialchars($saved_key) ?></div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-outline-primary flex-fill" id="btnCopyKey" onclick="copySavedKey()">
                            <i class="fas fa-copy me-1"></i> <span id="copyBtnText">Salin Kunci</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary flex-fill" onclick="downloadKeyBackup('<?= htmlspecialchars($saved_username) ?>', '<?= htmlspecialchars($saved_key) ?>')">
                            <i class="fas fa-download me-1"></i> Download .txt
                        </button>
                    </div>

                    <div class="alert alert-warning border-0 small mb-4">
                        <i class="fas fa-exclamation-triangle me-1"></i> <b>Penting:</b> Kunci ini tidak disimpan dalam teks biasa di server. Jika hilang, data tidak dapat dibuka.
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" onclick="switchToLoginTabWithKey('<?= htmlspecialchars($saved_key) ?>')" class="btn btn-primary-action text-white text-center">
                            Masuk ke Dashboard Sekarang <i class="fas fa-sign-in-alt ms-2"></i>
                        </button>
                    </div>

                <?php else: ?>

                    <?php if ($userCount == 0): ?>
                        <div class="alert alert-info border-0 shadow-sm mb-3 small">
                            <i class="fas fa-info-circle me-1"></i> <b>Selamat Datang!</b> Silakan buat Private Key pertama Anda untuk mengamankan data.
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <input type="hidden" name="action_type" value="setup">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">Nama Pengguna (Username)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($existingUser['username'] ?? 'admin') ?>" required autocomplete="off" placeholder="Nama admin">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold text-dark mb-0">Private Key Baru</label>
                                <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 fw-semibold" onclick="generateRandomKey()">
                                    <i class="fas fa-magic me-1"></i> Generate Acak
                                </button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="private_key" id="setupKeyInput" class="form-control with-icon" required autocomplete="off" placeholder="Minimal 6 karakter">
                                <button class="btn btn-toggle-eye" type="button" onclick="togglePasswordVisibility('setupKeyInput', 'setupEyeIcon1')">
                                    <i class="fas fa-eye" id="setupEyeIcon1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">Konfirmasi Private Key Baru</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                <input type="password" name="confirm_private_key" id="setupConfirmKeyInput" class="form-control with-icon" required autocomplete="off" placeholder="Ulangi Private Key">
                                <button class="btn btn-toggle-eye" type="button" onclick="togglePasswordVisibility('setupConfirmKeyInput', 'setupEyeIcon2')">
                                    <i class="fas fa-eye" id="setupEyeIcon2"></i>
                                </button>
                            </div>
                        </div>

                        <?php if ($existingUser): ?>
                            <div class="form-check mb-3 p-3 bg-light rounded-3 border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="reset_transactions" value="1" id="resetTransactionsCheck">
                                <label class="form-check-label small text-muted" for="resetTransactionsCheck">
                                    <b class="text-danger">Bersihkan data transaksi lama</b> (Direkomendasikan jika ganti key agar tidak error dekripsi).
                                </label>
                            </div>
                        <?php endif; ?>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="agreementCheck" required>
                            <label class="form-check-label small text-muted" for="agreementCheck">
                                Saya paham bahwa <b>Private Key tidak dapat dipulihkan</b> jika hilang.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary-action w-100 text-white">
                            <i class="fas fa-save me-2"></i> Simpan & Terapkan Key
                        </button>
                    </form>

                    <div class="text-center mt-3 pt-3 border-top">
                        <a href="javascript:void(0)" onclick="switchToLoginTab()" class="text-decoration-none small back-link">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Form Login
                        </a>
                    </div>

                <?php endif; ?>

            </div>

        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="../landing/index.php" class="text-decoration-none back-link">
                <i class="fas fa-home me-1"></i> Landing Page
            </a>
            <span class="text-muted small"><i class="fas fa-shield-alt text-success me-1"></i> MoneyTrack</span>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function switchToSetupTab() {
    const tabEl = document.getElementById('pills-setup-tab');
    if (tabEl) {
        bootstrap.Tab.getOrCreateInstance(tabEl).show();
    }
}

function switchToLoginTab() {
    const tabEl = document.getElementById('pills-login-tab');
    if (tabEl) {
        bootstrap.Tab.getOrCreateInstance(tabEl).show();
    }
}

function switchToLoginTabWithKey(key) {
    switchToLoginTab();
    const loginInput = document.getElementById('loginKeyInput');
    if (loginInput) {
        loginInput.value = key;
        loginInput.focus();
    }
}

function generateRandomKey() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let result = '';
    const array = new Uint32Array(16);
    window.crypto.getRandomValues(array);
    for (let i = 0; i < 16; i++) {
        result += chars[array[i] % chars.length];
    }
    
    const keyInput = document.getElementById('setupKeyInput');
    const confirmInput = document.getElementById('setupConfirmKeyInput');
    if (keyInput && confirmInput) {
        keyInput.value = result;
        confirmInput.value = result;
        keyInput.type = 'text';
        confirmInput.type = 'text';
        
        const eye1 = document.getElementById('setupEyeIcon1');
        const eye2 = document.getElementById('setupEyeIcon2');
        if (eye1) { eye1.classList.remove('fa-eye'); eye1.classList.add('fa-eye-slash'); }
        if (eye2) { eye2.classList.remove('fa-eye'); eye2.classList.add('fa-eye-slash'); }
    }
}

function copySavedKey() {
    const keyText = document.getElementById('savedKeyText')?.innerText.trim() || '';
    if (!keyText) return;
    navigator.clipboard.writeText(keyText).then(() => {
        const btnText = document.getElementById('copyBtnText');
        if (btnText) {
            btnText.innerText = 'Tersalin!';
            setTimeout(() => {
                btnText.innerText = 'Salin Kunci';
            }, 2000);
        }
    });
}

function downloadKeyBackup(username, key) {
    const content = `=========================================\n` +
                    `  MONEYTRACK - PRIVATE KEY BACKUP FILE  \n` +
                    `=========================================\n` +
                    `Username    : ${username}\n` +
                    `Private Key : ${key}\n` +
                    `Dibuat pada : ${new Date().toLocaleString()}\n` +
                    `-----------------------------------------\n` +
                    `CATATAN PENTING:\n` +
                    `- Simpan file ini di tempat yang sangat aman.\n` +
                    `- Kunci ini diperlukan untuk login dan membuka\n` +
                    `  seluruh nominal transaksi terenkripsi AES-256-GCM.\n` +
                    `=========================================\n`;
    const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `moneytrack_key_${username || 'admin'}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
</body>
</html>
