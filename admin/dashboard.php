<?php
// admin/dashboard.php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';

$private_key = $_SESSION['private_key'];

// Variabel untuk menampung total perhitungan
$total_pemasukan = 0;
$total_pengeluaran = 0;
$error_dekripsi = false;

// Ambil semua transaksi untuk dihitung
$query = $conn->query("SELECT jenis, nominal FROM transaksi");
if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $nominal_dec = decryptData($row['nominal'], $private_key);
        
        if ($nominal_dec !== false) {
            $nominal_angka = (float)$nominal_dec;
            if ($row['jenis'] == 'Pemasukan') {
                $total_pemasukan += $nominal_angka;
            } else {
                $total_pengeluaran += $nominal_angka;
            }
        } else {
            $error_dekripsi = true;
        }
    }
}

$saldo_terkini = $total_pemasukan - $total_pengeluaran;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Dashboard Utama</h2>
    <span class="badge bg-success py-2 px-3 shadow-sm" style="border-radius: 8px;">
        <i class="fas fa-lock me-1"></i> AES-256-GCM Aktif
    </span>
</div>

<div class="alert bg-white shadow-sm border-0 d-flex align-items-center mb-4" style="border-left: 4px solid #009ef7 !important; border-radius: 12px;">
    <div class="me-3 fs-3 text-primary">
        <i class="fas fa-info-circle"></i>
    </div>
    <div>
        Selamat datang, <b><?= htmlspecialchars($_SESSION['username']) ?></b>. Semua angka di bawah ini dihitung secara <i>real-time</i> melalui proses dekripsi menggunakan Private Key Anda.
    </div>
</div>

<?php if ($error_dekripsi): ?>
    <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4" style="border-radius: 12px;">
        <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
        <div>
            <b>Peringatan:</b> Ada beberapa data transaksi yang gagal didekripsi. Pastikan Private Key tidak berubah atau data di database tidak dimanipulasi.
        </div>
    </div>
<?php endif; ?>

<!-- Card Statistik Modern -->
<div class="row mb-4">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-arrow-down fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted fw-semibold mb-1 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Total Pemasukan</h6>
                    <h3 class="fw-bold mb-0 text-dark">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-arrow-up fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted fw-semibold mb-1 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Total Pengeluaran</h6>
                    <h3 class="fw-bold mb-0 text-dark">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 16px; background: linear-gradient(135deg, #009ef7 0%, #007bb5 100%);">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-white bg-opacity-25 text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-wallet fs-4"></i>
                </div>
                <div class="text-white">
                    <h6 class="fw-semibold mb-1 text-uppercase text-white-50" style="font-size: 0.85rem; letter-spacing: 0.5px;">Saldo Terkini</h6>
                    <h3 class="fw-bold mb-0">Rp <?= number_format($saldo_terkini, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan Bagian Chart -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-transparent py-4 border-0">
                <h5 class="m-0 fw-bold text-dark">Grafik Pemasukan vs Pengeluaran</h5>
            </div>
            <div class="card-body px-4 pb-5 pt-0">
                <div style="position: relative; height: 350px; width: 100%;">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('financeChart').getContext('2d');
        
        // Gradient untuk bar Pemasukan
        const gradientIn = ctx.createLinearGradient(0, 0, 0, 400);
        gradientIn.addColorStop(0, 'rgba(80, 205, 137, 1)'); // Hijau Mazer
        gradientIn.addColorStop(1, 'rgba(80, 205, 137, 0.4)');

        // Gradient untuk bar Pengeluaran
        const gradientOut = ctx.createLinearGradient(0, 0, 0, 400);
        gradientOut.addColorStop(0, 'rgba(241, 65, 108, 1)'); // Merah Mazer
        gradientOut.addColorStop(1, 'rgba(241, 65, 108, 0.4)');

        const financeChart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    label: 'Total',
                    data: [<?= $total_pemasukan ?>, <?= $total_pengeluaran ?>],
                    backgroundColor: [gradientIn, gradientOut],
                    hoverBackgroundColor: [
                        'rgba(80, 205, 137, 1)', 
                        'rgba(241, 65, 108, 1)'
                    ],
                    borderWidth: 0,
                    borderRadius: 10,
                    barPercentage: 0.4, // Bikin batang grafiknya lebih ramping & elegan
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false 
                    },
                    tooltip: {
                        backgroundColor: '#1e1e2d',
                        padding: 12,
                        titleFont: { size: 14, family: "'Poppins', sans-serif" },
                        bodyFont: { size: 14, family: "'Poppins', sans-serif" },
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#eff2f5',
                            drawBorder: false,
                            borderDash: [5, 5] // Grid putus-putus biar lebih clean
                        },
                        ticks: {
                            font: { family: "'Poppins', sans-serif", size: 11 },
                            color: '#a1a5b7',
                            padding: 10,
                            callback: function(value) {
                                if(value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + ' Jt';
                                } else if(value >= 1000) {
                                    return 'Rp ' + (value / 1000) + ' Rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Poppins', sans-serif", size: 13, weight: '500' },
                            color: '#3f4254',
                            padding: 10
                        }
                    }
                }
            }
        });
    });
</script>

<?php
require_once '../layouts/footer.php';
?>