<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTrack - Secure Personal Finance AES-256-GCM</title>
    
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
            color: #2f3640;
        }

        /* Navbar Modern */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 16px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #1e1e2d !important;
            font-size: 1.45rem;
            letter-spacing: 0.5px;
        }

        .nav-link { 
            font-weight: 500; 
            color: #5e6278 !important; 
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: 0.2s;
        }
        
        .nav-link.active, .nav-link:hover { 
            color: #009ef7 !important; 
            background-color: rgba(0, 158, 247, 0.06);
        }

        .btn-login-nav {
            background-color: #1e1e2d;
            color: white !important;
            font-weight: 600;
            padding: 10px 22px;
            border-radius: 10px;
            transition: 0.3s;
            border: none;
        }

        .btn-login-nav:hover {
            background-color: #009ef7;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 158, 247, 0.3);
        }

        /* Hero Section */
        .hero-section {
            padding: 140px 0 90px;
            min-height: 85vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f4f6f9 0%, #e2e8f0 100%);
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 158, 247, 0.15) 0%, rgba(0, 158, 247, 0) 70%);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .project-badge {
            display: inline-flex;
            align-items: center;
            background-color: #e8f4fd;
            color: #009ef7;
            border: 1px solid rgba(0, 158, 247, 0.2);
            padding: 6px 16px;
            border-radius: 50rem;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero-title {
            font-weight: 800;
            font-size: 3.2rem;
            line-height: 1.25;
            color: #181c32;
            margin-bottom: 20px;
        }

        .hero-title span {
            color: #009ef7;
            position: relative;
            display: inline-block;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #5e6278;
            line-height: 1.85;
            margin-bottom: 35px;
            font-weight: 400;
        }

        .btn-cta-primary {
            background-color: #009ef7;
            color: white;
            font-weight: 600;
            padding: 14px 30px;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 10px 20px rgba(0, 158, 247, 0.25);
            display: inline-flex;
            align-items: center;
        }

        .btn-cta-primary:hover {
            background-color: #0095e8;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 14px 25px rgba(0, 158, 247, 0.35);
        }

        .btn-cta-secondary {
            background-color: #ffffff;
            color: #1e1e2d;
            font-weight: 600;
            padding: 14px 26px;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            border: 1px solid #e4e6ef;
            display: inline-flex;
            align-items: center;
        }

        .btn-cta-secondary:hover {
            background-color: #f5f8fa;
            color: #009ef7;
            transform: translateY(-2px);
        }

        /* Feature Card (Kanan Hero) */
        .preview-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.08);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255,255,255,0.8);
            transition: transform 0.4s ease;
        }

        .preview-card:hover {
            transform: translateY(-6px);
        }

        .floating-badge {
            position: absolute;
            top: -15px;
            right: 20px;
            background: linear-gradient(135deg, #009ef7 0%, #007bb5 100%);
            color: white;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.82rem;
            box-shadow: 0 8px 18px rgba(0, 158, 247, 0.3);
        }

        .icon-box {
            width: 65px;
            height: 65px;
            background: rgba(0, 158, 247, 0.1);
            color: #009ef7;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        /* Section Pillar Cards */
        .section-padding {
            padding: 90px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title {
            font-weight: 800;
            font-size: 2.3rem;
            color: #181c32;
            margin-bottom: 12px;
        }

        .section-subtitle {
            color: #7e8299;
            font-size: 1.05rem;
            max-width: 680px;
            margin: 0 auto;
        }

        .pillar-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 32px 26px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid #eff2f5;
            transition: all 0.3s ease;
            height: 100%;
        }

        .pillar-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.08);
            border-color: #009ef7;
        }

        .pillar-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 22px;
        }

        .step-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px 24px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.03);
            border-left: 4px solid #009ef7;
            height: 100%;
        }

        .step-number {
            width: 38px;
            height: 38px;
            background: #009ef7;
            color: #ffffff;
            font-weight: 700;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        /* Footer */
        .footer-custom {
            background-color: #1e1e2d;
            color: #a2a3b7;
            padding: 50px 0 30px;
        }

        .footer-brand {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.3rem;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.3rem; }
            .preview-card { margin-top: 40px; }
            .hero-section { padding: 110px 0 60px; }
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
                        <a href="../admin/login.php" class="btn btn-login-nav ms-lg-3">
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
                
                <!-- Kiri: Penjelasan Projek -->
                <div class="col-lg-7 pe-lg-5">
                    <div class="project-badge">
                        <i class="fas fa-university me-2"></i> Keamanan Data dan Informasi • Teknik Informatika UMMI
                    </div>
                    
                    <h1 class="hero-title">
                        Sistem Manajemen Keuangan Pribadi Berbasis <span>Kriptografi AES-256-GCM</span>
                    </h1>
                    
                    <p class="hero-subtitle">
                        <b>MoneyTrack</b> adalah aplikasi pembukuan finansial mandiri yang mengimplementasikan enkripsi simetris terotentikasi <b>AES-256-GCM</b>. Sistem ini tidak menyimpan password hash di database, mengamankan seluruh nominal transaksi secara <i>end-to-end</i> dengan <b>Private Key</b>, serta dilengkapi pertahanan <b>Honeypot Decoy</b> untuk mengecoh penyerang yang mencoba mengakses dengan kunci yang salah.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="../admin/login.php" class="btn btn-cta-primary">
                            Buka Sistem & Setup Key <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="keamanan.php" class="btn btn-cta-secondary">
                            Pelajari Algoritma <i class="fas fa-lock ms-2 text-primary"></i>
                        </a>
                    </div>

                    <div class="d-flex align-items-center mt-4 pt-3 text-muted small">
                        <div class="me-4"><i class="fas fa-user-tie text-primary me-1"></i> Pengembang: <b>Afdhal Tsany Nurrizki</b></div>
                        <div><i class="fas fa-check-shield text-success me-1"></i> Zero-Knowledge Architecture</div>
                    </div>
                </div>

                <!-- Kanan: Preview Card Fitur -->
                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="preview-card">
                        <div class="floating-badge">
                            <i class="fas fa-shield-virus me-1"></i> Active Defense
                        </div>
                        
                        <div class="icon-box">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        
                        <h4 class="fw-bold text-dark mb-2">Arsitektur Keamanan Berlapis</h4>
                        <p class="text-muted small mb-4">Pengamanan penuh mulai dari penyimpanan pangkalan data hingga antarmuka pengguna.</p>
                        
                        <div class="p-3 bg-light rounded-3 mb-3 border d-flex align-items-center">
                            <div class="me-3 text-primary fs-3">
                                <i class="fas fa-key"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Private Key Authentication</h6>
                                <small class="text-muted">Kunci hanya disimpan di memori sesi, tidak disimpan di DB.</small>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-3 border d-flex align-items-center">
                            <div class="me-3 text-success fs-3">
                                <i class="fas fa-database"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">AES-256-GCM Ciphertext</h6>
                                <small class="text-muted">Nominal & keterangan dienkripsi bersama Tag Integritas.</small>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border d-flex align-items-center">
                            <div class="me-3 text-danger fs-3">
                                <i class="fas fa-bug-slash"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Honeypot Decoy Mode</h6>
                                <small class="text-muted">Password salah tetap masuk, namun semua teks otomatis terenkripsi.</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Pilar Keamanan Projek Section -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="section-header">
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Fitur & Arsitektur Projek</span>
                <h2 class="section-title">Pilar Utama Keamanan MoneyTrack</h2>
                <p class="section-subtitle">
                    Dirancang untuk mengatasi ancaman pencurian basis data (<i>database breach</i>) dan penyadapan informasi finansial pribadi.
                </p>
            </div>

            <div class="row g-4">
                <!-- Card 1: AES-256-GCM -->
                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">AES-256-GCM</h5>
                        <p class="text-muted small mb-0">
                            Algoritma simetris 256-bit berstandar militer. Menggunakan <b>Galois/Counter Mode</b> yang menjamin kerahasiaan sekaligus integritas data melalui <i>Authentication Tag</i>.
                        </p>
                    </div>
                </div>

                <!-- Card 2: Zero-Knowledge -->
                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Zero-Knowledge Key</h5>
                        <p class="text-muted small mb-0">
                            Server tidak menyimpan password Anda. Otentikasi dilakukan dengan menguji validitas dekripsi token rahasia secara langsung saat login.
                        </p>
                    </div>
                </div>

                <!-- Card 3: Honeypot Protection -->
                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-mask"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Honeypot Defense</h5>
                        <p class="text-muted small mb-0">
                            Jika Private Key yang dimasukkan salah, pengguna tetap masuk ke dashboard umpan (decoy) dengan seluruh teks, angka, formulir, dan tombol terenkripsi total.
                        </p>
                    </div>
                </div>

                <!-- Card 4: Realtime Decryption -->
                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Kalkulasi On-The-Fly</h5>
                        <p class="text-muted small mb-0">
                            Perhitungan saldo kas, akumulasi pemasukan, pengeluaran, dan visualisasi grafik Chart.js dihitung secara langsung di memori tanpa mengekspos data mentah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Kerja Sistem Section -->
    <section class="section-padding" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="section-header">
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Mekanisme Kerja</span>
                <h2 class="section-title">Bagaimana Data Anda Diamankan?</h2>
                <p class="section-subtitle">
                    Alur kriptografis dari saat input data dicatat hingga ditampilkan di dashboard.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">1</div>
                        <h6 class="fw-bold text-dark mb-2">Pencatatan Transaksi</h6>
                        <p class="text-muted small mb-0">Admin menginput nominal dan keterangan pengeluaran/pemasukan melalui formulir terproteksi.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">2</div>
                        <h6 class="fw-bold text-dark mb-2">Enkripsi AES + IV</h6>
                        <p class="text-muted small mb-0">Sistem mengenerate <i>Initialization Vector (IV)</i> acak 12-byte dan mengenkripsi data sebelum masuk database.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">3</div>
                        <h6 class="fw-bold text-dark mb-2">Ciphertext di Database</h6>
                        <p class="text-muted small mb-0">Di tabel MariaDB, nominal tersimpan dalam string acak Base64 yang tidak dapat dibaca tanpa Private Key.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">4</div>
                        <h6 class="fw-bold text-dark mb-2">Dekripsi Real-Time</h6>
                        <p class="text-muted small mb-0">Data hanya bisa terbuka saat admin memasukkan Private Key yang tepat saat sesi login.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <div class="footer-brand mb-2">
                        <i class="fas fa-shield-alt text-primary me-2"></i>MoneyTrack
                    </div>
                    <p class="small text-muted mb-0">
                        Proyek Mata Kuliah Keamanan Data dan Informasi • Program Studi Teknik Informatika, Universitas Muhammadiyah Sukabumi.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="text-white small fw-semibold mb-1">Pengembang: Afdhal Tsany Nurrizki</div>
                    <div class="small text-muted">&copy; 2026 MoneyTrack. Dilindungi Enkripsi AES-256-GCM.</div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
