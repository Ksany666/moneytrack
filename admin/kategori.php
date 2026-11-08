<?php
// admin/kategori.php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';

$private_key = $_SESSION['private_key']; // Ambil kunci dari session
$pesan = '';

// 1. Proses Tambah Kategori
if (isset($_POST['tambah'])) {
    $jenis = $_POST['jenis'];
    $nama_kategori_plain = $_POST['nama_kategori'];

    // Enkripsi nama kategori sebelum disimpan
    $nama_kategori_enc = encryptData($nama_kategori_plain, $private_key);

    $stmt = $conn->prepare("INSERT INTO kategori (jenis, nama_kategori) VALUES (?, ?)");
    $stmt->bind_param("ss", $jenis, $nama_kategori_enc);
    
    if ($stmt->execute()) {
        $pesan = '<div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i class="fas fa-check-circle me-2"></i>Kategori berhasil ditambahkan!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } else {
        $pesan = '<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><i class="fas fa-times-circle me-2"></i>Gagal menambahkan kategori.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

// 2. Proses Hapus Kategori
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $pesan = '<div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i class="fas fa-check-circle me-2"></i>Kategori berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Kategori</h2>
    <!-- Tombol Pemicu Modal -->
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
        <i class="fas fa-plus me-2"></i> Tambah Kategori
    </button>
</div>

<?= $pesan ?>

<!-- Tabel Daftar Kategori Full Width -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold">Daftar Kategori</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="10%">No</th>
                        <th width="20%">Jenis</th>
                        <th width="50%">Nama Kategori (Hasil Dekripsi)</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
                    
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            // Dekripsi nama kategori untuk ditampilkan
                            $nama_kategori_dec = decryptData($row['nama_kategori'], $private_key);
                            
                            // Jika dekripsi gagal
                            if ($nama_kategori_dec === false) {
                                $nama_kategori_dec = "<span class='text-danger'><i>Data rusak / Kunci salah</i></span>";
                            }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span class="badge <?= $row['jenis'] == 'Pemasukan' ? 'bg-success' : 'bg-danger' ?>">
                                <?= htmlspecialchars($row['jenis']) ?>
                            </span>
                        </td>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($nama_kategori_dec) ?></td>
                        <td>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-4'>Belum ada kategori</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahKategoriLabel">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required placeholder="Contoh: Gaji Bulanan, Beli Bensin, dll">
                        <small class="form-text text-muted">Teks ini akan otomatis dienkripsi sebelum disimpan ke database.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../layouts/footer.php';
?>