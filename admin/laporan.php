<?php
// admin/laporan.php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';

$private_key = $_SESSION['private_key'];
?>

<!-- Tambahan CSS khusus untuk Print -->
<style>
    @media print {
        #sidebar, .btn, header, .page-heading h2, hr.d-print-none {
            display: none !important;
        }
        .main-content {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h2>Laporan Keuangan</h2>
    <button onclick="window.print()" class="btn btn-primary shadow-sm">
        <i class="fas fa-print me-2"></i> Cetak Laporan
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Laporan Mutasi Keuangan - MoneyTrack</h3>
            <p class="text-muted">Dicetak pada: <?= date('d F Y H:i') ?></p>
        </div>
        <hr class="d-print-none mb-4">

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Jenis</th>
                        <th width="20%">Kategori</th>
                        <th width="25%">Keterangan</th>
                        <th width="20%">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_in = 0;
                    $total_out = 0;
                    
                    $query = $conn->query("SELECT t.*, k.nama_kategori FROM transaksi t LEFT JOIN kategori k ON t.kategori_id = k.id ORDER BY t.tanggal ASC");
                    
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            $nama_kat_dec = decryptData($row['nama_kategori'], $private_key);
                            $nominal_dec = decryptData($row['nominal'], $private_key);
                            $keterangan_dec = decryptData($row['keterangan'], $private_key);
                            
                            $is_error = ($nama_kat_dec === false || $nominal_dec === false || $keterangan_dec === false);
                            
                            if (!$is_error) {
                                $nom = (float)$nominal_dec;
                                if ($row['jenis'] == 'Pemasukan') $total_in += $nom;
                                else $total_out += $nom;
                            }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        
                        <?php if ($is_error): ?>
                            <td colspan="4" class="text-danger"><i>[Data Gagal Didekripsi]</i></td>
                        <?php else: ?>
                            <td><?= $row['jenis'] ?></td>
                            <td><?= htmlspecialchars($nama_kat_dec) ?></td>
                            <td><?= htmlspecialchars($keterangan_dec) ?></td>
                            <td class="text-end <?= $row['jenis'] == 'Pemasukan' ? 'text-success' : 'text-danger' ?>">
                                <?= $row['jenis'] == 'Pemasukan' ? '+' : '-' ?> Rp <?= number_format($nom, 0, ',', '.') ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-3'>Data transaksi kosong</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">Total Pemasukan:</td>
                        <td class="text-end text-success">Rp <?= number_format($total_in, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end">Total Pengeluaran:</td>
                        <td class="text-end text-danger">Rp <?= number_format($total_out, 0, ',', '.') ?></td>
                    </tr>
                    <tr class="table-primary">
                        <td colspan="5" class="text-end fs-5">Saldo Akhir:</td>
                        <td class="text-end fs-5">Rp <?= number_format($total_in - $total_out, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="mt-5 text-end">
            <p>Sukabumi, <?= date('d F Y') ?></p>
            <br><br><br>
            <p class="fw-bold"><u><?= htmlspecialchars($_SESSION['username']) ?></u><br>Administrator</p>
        </div>
    </div>
</div>

<?php
require_once '../layouts/footer.php';
?>