CREATE DATABASE IF NOT EXISTS db_moneytrack;
USE db_moneytrack;

-- Tabel Users untuk Admin
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `auth_verify` text NOT NULL, -- Menyimpan teks terenkripsi untuk validasi Private Key
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Kategori (Pemasukan/Pengeluaran)
CREATE TABLE `kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `nama_kategori` text NOT NULL, -- Terenkripsi
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Transaksi
CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL, -- Tanggal tidak dienkripsi agar mudah difilter/sorting
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nominal` text NOT NULL, -- Terenkripsi
  `keterangan` text NOT NULL, -- Terenkripsi
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;