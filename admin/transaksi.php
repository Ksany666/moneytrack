<?php
// admin/transaksi.php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';

$private_key = $_SESSION['private_key'];
$pesan = '';

// Proses Tambah Transaksi
if (isset($_POST['tambah'])) {
    $tanggal = $_POST['tanggal'];
    $kategori_id = $_POST['kategori_id'];
    
    // 1. Cari tahu jenis transaksi berdasarkan kategori yang dipilih
    $stmt_kategori = $conn->prepare("SELECT jenis FROM kategori WHERE id = ?");
    $stmt_kategori->bind_param("i", $kategori_id);
    $stmt_kategori->execute();
    $hasil_kategori = $stmt_kategori->get_result();
    
    if ($hasil_kategori->num_rows > 0) {
        $row_kategori = $hasil_kategori->fetch_assoc();
        $jenis = $row_kategori['jenis']; // Otomatis dapat Pemasukan atau Pengeluaran
        
        // 2. Data sensitif yang akan dienkripsi
        $nominal_plain = $_POST['nominal'];
        $keterangan_plain = $_POST['keterangan'];

        // 3. Enkripsi nominal dan keterangan
        $nominal_enc = encryptData($nominal_plain, $private_key);
        $keterangan_enc = encryptData($keterangan_plain, $private_key);

        // 4. Simpan ke database
        $stmt = $conn->prepare("INSERT INTO transaksi (tanggal, jenis, kategori_id, nominal, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $tanggal, $jenis, $kategori_id, $nominal_enc, $keterangan_enc);
        
        if ($stmt->execute()) {
            $pesan = '<div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i class="fas fa-check-circle me-2"></i>Transaksi berhasil disimpan dan dienkripsi!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            $pesan = '<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><i class="fas fa-times-circle me-2"></i>Gagal menyimpan transaksi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    } else {
         $pesan = '<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><i class="fas fa-times-circle me-2"></i>Kategori tidak ditemukan.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

// Proses Hapus Transaksi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $pesan = '<div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i class="fas fa-check-circle me-2"></i>Transaksi berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Transaksi</h2>
    <!-- Tombol Pemicu Modal -->
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTransaksi">
        <i class="fas fa-plus me-2"></i> Tambah Transaksi
    </button>
</div>

<?= $pesan ?>

<!-- Tabel Daftar Transaksi Full Width -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold">Riwayat Transaksi (Hasil Dekripsi)</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Join tabel transaksi dengan kategori
                    $query = $conn->query("SELECT t.*, k.nama_kategori FROM transaksi t LEFT JOIN kategori k ON t.kategori_id = k.id ORDER BY t.tanggal DESC, t.id DESC");
                    
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            // Proses Dekripsi On-The-Fly
                            $nama_kat_dec = decryptData($row['nama_kategori'], $private_key);
                            $nominal_dec = decryptData($row['nominal'], $private_key);
                            $keterangan_dec = decryptData($row['keterangan'], $private_key);
                            
                            // Cek jika dekripsi gagal
                            $is_error = ($nama_kat_dec === false || $nominal_dec === false || $keterangan_dec === false);
                    ?>
                    <tr>
                        <td class="align-middle"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        
                        <?php if ($is_error): ?>
                            <td colspan="3" class="text-danger align-middle"><i><i class="fas fa-exclamation-triangle"></i> Gagal mendekripsi data. Kunci tidak valid.</i></td>
                        <?php else: ?>
                            <td class="align-middle">
                                <span class="badge <?= $row['jenis'] == 'Pemasukan' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($nama_kat_dec) ?>
                                </span>
                            </td>
                            <td class="align-middle text-dark fw-medium"><?= htmlspecialchars($keterangan_dec) ?></td>
                            <td class="align-middle fw-bold <?= $row['jenis'] == 'Pemasukan' ? 'text-success' : 'text-danger' ?>">
                                <?= $row['jenis'] == 'Pemasukan' ? '+' : '-' ?> Rp <?= number_format((float)$nominal_dec, 0, ',', '.') ?>
                            </td>
                        <?php endif; ?>

                        <td class="align-middle">
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus transaksi ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada riwayat transaksi</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="modalTambahTransaksi" tabindex="-1" aria-labelledby="modalTambahTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahTransaksiLabel">Tambah Transaksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php
                                $q_kat = $conn->query("SELECT * FROM kategori ORDER BY jenis ASC");
                                while ($k = $q_kat->fetch_assoc()) {
                                    $nama_kat_dec = decryptData($k['nama_kategori'], $private_key);
                                    if ($nama_kat_dec !== false) {
                                        // Label jenis masih ditampilkan biar user tau ini kategori untuk apa
                                        echo "<option value='{$k['id']}'>[{$k['jenis']}] {$nama_kat_dec}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control" required placeholder="Contoh: 50000">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" required placeholder="Catatan transaksi..."></textarea>
                        <small class="form-text text-muted mt-2 d-block"><i class="fas fa-lock text-success"></i> Nominal & Keterangan akan otomatis dienkripsi dengan AES-256-GCM.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../layouts/footer.php';
?>