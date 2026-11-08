<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keamanan - MoneyTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; overflow-x: hidden; padding-top: 80px; }
        .navbar-custom { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05); padding: 15px 0; }
        .navbar-brand { font-weight: 700; color: #1e1e2d !important; font-size: 1.5rem; }
        .nav-link { font-weight: 500; color: #5e6278 !important; }
        .nav-link.active, .nav-link:hover { color: #009ef7 !important; }
        .btn-login { background-color: #1e1e2d; color: white; font-weight: 500; padding: 10px 24px; border-radius: 8px; transition: 0.3s; }
        .btn-login:hover { background-color: #009ef7; color: white; transform: translateY(-2px); }
        .content-section { background: white; border-radius: 20px; padding: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 50px; }
        .feature-icon { width: 50px; height: 50px; background: rgba(0,158,247,0.1); color: #009ef7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 15px; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-shield-alt text-primary me-2"></i>MoneyTrack</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="tentang.php">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link active" href="keamanan.php">Sistem Keamanan</a></li>
                    <li class="nav-item"><a href="../admin/login.php" class="btn btn-login ms-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="content-section">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Arsitektur <span class="text-primary">Kriptografi</span></h2>
                <p class="text-muted">Mengenal implementasi AES-256-GCM pada aplikasi</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-4 border rounded-4 h-100">
                        <div class="feature-icon"><i class="fas fa-key"></i></div>
                        <h5 class="fw-bold mb-3">Apa itu AES-256?</h5>
                        <p class="text-muted text-justify">
                            Advanced Encryption Standard (AES) adalah algoritma enkripsi simetris yang menjadi standar industri. Angka <b>256</b> merujuk pada panjang kunci (key length) sebesar 256-bit, yang menjadikannya sangat tahan terhadap serangan <i>brute-force</i> karena memiliki kombinasi kunci yang nyaris tak terbatas.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-4 border rounded-4 h-100">
                        <div class="feature-icon"><i class="fas fa-check-double"></i></div>
                        <h5 class="fw-bold mb-3">Keunggulan Mode GCM</h5>
                        <p class="text-muted text-justify">
                            Galois/Counter Mode (GCM) adalah mode operasi untuk kriptografi blok. Tidak seperti mode standar (misal: CBC), GCM menyediakan <i>Authenticated Encryption</i>. Artinya, GCM tidak hanya menyembunyikan data (Kerahasiaan), tetapi juga memastikan bahwa data tidak dimodifikasi oleh pihak ketiga di database (Integritas).
                        </p>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="p-4 bg-light rounded-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-cogs text-primary me-2"></i>Cara Kerja di MoneyTrack</h5>
                        <ul class="text-muted mb-0" style="line-height: 1.8;">
                            <li>Sistem tidak menyimpan <i>password hash</i> biasa. Admin menggunakan <b>Private Key</b> rahasia untuk masuk ke dalam sistem.</li>
                            <li>Kunci tersebut tidak pernah disimpan ke dalam database, melainkan hanya dipegang oleh sistem di memori (Session) selama admin aktif.</li>
                            <li>Setiap input nominal dan keterangan dari pengguna akan digabung dengan <i>Initialization Vector (IV)</i> dan dienkripsi menjadi teks acak (Ciphertext).</li>
                            <li>Jika seseorang membobol database, mereka hanya akan melihat teks acak tanpa bisa membacanya tanpa Private Key yang benar.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>