<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang MoneyTrack</title>
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
                    <li class="nav-item"><a class="nav-link active" href="tentang.php">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="keamanan.php">Sistem Keamanan</a></li>
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
                <h2 class="fw-bold text-dark">Tentang <span class="text-primary">MoneyTrack</span></h2>
                <p class="text-muted">Sistem Informasi Manajemen Keuangan Pribadi Berbasis Web</p>
            </div>
            
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <p class="lead text-muted" style="line-height: 1.8;">
                        MoneyTrack adalah aplikasi yang dirancang khusus untuk mencatat, mengelola, dan memantau arus kas pribadi dengan tingkat privasi yang tinggi. Berbeda dengan pencatatan keuangan konvensional, aplikasi ini tidak membiarkan data sensitif seperti nominal dan keterangan transaksi tersimpan dalam bentuk teks biasa (<i>plaintext</i>).
                    </p>
                    <p class="text-muted" style="line-height: 1.8;">
                        Aplikasi ini dikembangkan untuk memenuhi tugas mata kuliah Keamanan Data dan Informasi di program studi Teknik Informatika, Universitas Muhammadiyah Sukabumi. Pengembangan sistem ini berfokus pada implementasi kriptografi simetris modern untuk melindungi data pengguna dari ancaman kebocoran pangkalan data (<i>database breach</i>).
                    </p>
                    
                    <div class="mt-5 p-4 bg-light rounded-4 text-start">
                        <h5 class="fw-bold mb-3"><i class="fas fa-laptop-code text-primary me-2"></i>Informasi Pengembang</h5>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th width="30%" class="text-muted">Nama Pengembang</th>
                                <td class="fw-semibold">Afdhal Tsany Nurrizki</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Institusi</th>
                                <td class="fw-semibold">Universitas Muhammadiyah Sukabumi</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Teknologi Inti</th>
                                <td class="fw-semibold">PHP 8, MariaDB, Bootstrap 5, OpenSSL (AES-256-GCM)</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>