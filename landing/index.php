<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTrack - Secure Your Finance</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        /* Navbar Modern */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #1e1e2d !important;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .nav-link { 
            font-weight: 500; 
            color: #5e6278 !important; 
        }
        
        .nav-link.active, .nav-link:hover { 
            color: #009ef7 !important; 
        }

        .btn-login {
            background-color: #1e1e2d;
            color: white;
            font-weight: 500;
            padding: 10px 24px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #009ef7;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 158, 247, 0.3);
        }

        /* Hero Section */
        .hero-section {
            padding: 120px 0 80px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f4f6f9 0%, #e2e8f0 100%);
            position: relative;
        }

        /* Dekorasi Lingkaran di Background */
        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(0, 158, 247, 0.1), rgba(0, 158, 247, 0));
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
            line-height: 1.2;
            color: #181c32;
            margin-bottom: 20px;
        }

        .hero-title span {
            color: #009ef7;
            position: relative;
            display: inline-block;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: #5e6278;
            line-height: 1.8;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .btn-cta {
            background-color: #009ef7;
            color: white;
            font-weight: 600;
            padding: 15px 35px;
            border-radius: 10px;
            font-size: 1.1rem;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 10px 20px rgba(0, 158, 247, 0.2);
        }

        .btn-cta:hover {
            background-color: #0095e8;
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0, 158, 247, 0.3);
            color: white;
        }

        /* Feature Cards (Ilustrasi Kanan) */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255,255,255,0.5);
            transform: perspective(1000px) rotateY(-5deg);
            transition: transform 0.5s ease;
        }

        .feature-card:hover {
            transform: perspective(1000px) rotateY(0deg) translateY(-10px);
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: rgba(0, 158, 247, 0.1);
            color: #009ef7;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 25px;
        }
        
        .floating-badge {
            position: absolute;
            top: -20px;
            right: -20px;
            background: #50cd89;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 10px 20px rgba(80, 205, 137, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Responsif */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .feature-card { margin-top: 50px; transform: none; }
            .feature-card:hover { transform: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-shield-alt text-primary me-2"></i>MoneyTrack
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="tentang.php">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="keamanan.php">Sistem Keamanan</a></li>
                    <li class="nav-item">
                        <a href="../admin/login.php" class="btn btn-login ms-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                
                <!-- Kiri: Teks -->
                <div class="col-lg-6 pe-lg-5">
                    <h1 class="hero-title">
                        Kelola Keuangan Pribadi dengan <span>Aman</span>
                    </h1>
                    <p class="hero-subtitle">
                        MoneyTrack melindungi privasi finansial Anda. Setiap transaksi dan kategori dienkripsi menggunakan standar militer <b>AES-256-GCM</b>. Data Anda hanya bisa dibaca oleh Anda.
                    </p>
                    <a href="../admin/login.php" class="btn btn-cta">
                        Mulai Sistem Sekarang <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>

                <!-- Kanan: Visual Card (Pengganti Placeholder) -->
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="feature-card">
                        <div class="floating-badge">
                            <i class="fas fa-lock me-1"></i> AES-256-GCM
                        </div>
                        
                        <div class="icon-box">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        
                        <h3 class="fw-bold mb-3">Private Key Authentication</h3>
                        <p class="text-muted mb-4">Sistem kami tidak menggunakan password biasa. Akses dashboard Anda dikunci menggunakan Private Key rahasia yang tidak pernah disimpan di dalam database.</p>
                        
                        <div class="d-flex align-items-center mt-4 p-3 bg-light rounded-3">
                            <div class="me-3 text-success fs-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 m-0">Data Terenkripsi</h6>
                                <small class="text-muted">Nominal & Keterangan Transaksi aman.</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>