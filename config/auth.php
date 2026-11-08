
<?php

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');

    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);

    session_start();
}

// Membuat token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fungsi memeriksa apakah admin sudah login
function cekLogin()
{
    if (empty($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Fungsi membersihkan output
function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>
