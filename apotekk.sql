-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jul 2020 pada 08.41
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apotekk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_obat`
--

CREATE TABLE `tb_obat` (
  `obat_id` int(11) NOT NULL,
  `obat_nama` varchar(50) NOT NULL,
  `obat_stok` int(11) NOT NULL,
  `obat_beli` int(11) NOT NULL,
  `obat_jual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_obat`
--

INSERT INTO `tb_obat` (`obat_id`, `obat_nama`, `obat_stok`, `obat_beli`, `obat_jual`) VALUES
(1, 'Ethambutol', 163, 8000, 9000),
(2, 'Paracetamol', 171, 5000, 7000),
(8, 'ACNOL 10ML', 3, 9000, 10000),
(9, 'COUNTERPAIN 15 GR', 47, 20000, 22000),
(10, 'FATIGON C PLUS', 40, 4000, 5000),
(11, 'FLUTAMOL 60ML', 60, 12000, 13000),
(12, 'GRALYSIN 60 ML', 77, 10000, 12000),
(13, 'coba', 3, 3, 4),
(14, 'coba', 3, 3, 4),
(15, 'coba', 3, 3, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembelian`
--

CREATE TABLE `tb_pembelian` (
  `pembelian_id` int(11) NOT NULL,
  `pembelian_faktur` varchar(255) NOT NULL DEFAULT '0',
  `pembelian_tanggal_faktur` date NOT NULL,
  `pembelian_tanggal_masuk` date NOT NULL,
  `pembelian_id_transaksi` char(30) NOT NULL,
  `pembelian_tanggal` date NOT NULL,
  `pembelian_supplier` varchar(50) NOT NULL,
  `pembelian_subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_pembelian`
--

INSERT INTO `tb_pembelian` (`pembelian_id`, `pembelian_faktur`, `pembelian_tanggal_faktur`, `pembelian_tanggal_masuk`, `pembelian_id_transaksi`, `pembelian_tanggal`, `pembelian_supplier`, `pembelian_subtotal`) VALUES
(10, '0', '0000-00-00', '0000-00-00', 'PMB-20191204065008', '2019-12-04', 'PT Andi', 900000),
(11, '0', '0000-00-00', '0000-00-00', 'PMB-20191205055358', '2019-12-05', 'PT Andi', 910000),
(12, '343', '0000-00-00', '0000-00-00', 'PMB-20200724101536', '2020-07-24', 'PT Andi', 32000),
(13, 'erer', '0000-00-00', '0000-00-00', 'PMB-20200724105622', '2020-07-24', 'PT Andi', 60000),
(14, 'erer', '0000-00-00', '0000-00-00', 'PMB-20200724105705', '2020-07-24', 'PT Andi', 60000),
(15, 'erer', '0000-00-00', '0000-00-00', 'PMB-20200724105733', '2020-07-24', 'PT Andi', 60000),
(16, 'erer', '0000-00-00', '0000-00-00', 'PMB-20200724105803', '2020-07-24', 'PT Andi', 60000),
(17, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727114615', '2020-07-27', 'PT Andi', 344000),
(18, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727114641', '2020-07-27', 'PT Andi', 344000),
(19, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727114746', '2020-07-27', 'PT Andi', 344000),
(20, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727114903', '2020-07-27', 'PT Andi', 344000),
(21, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727115116', '2020-07-27', 'PT Andi', 344000),
(22, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727115727', '2020-07-27', 'PT Andi', 344000),
(23, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727115814', '2020-07-27', 'PT Andi', 105000),
(24, '2345676', '0000-00-00', '0000-00-00', 'PMB-20200727121138', '2020-07-27', 'PT Andi', 105000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembelian_detail`
--

CREATE TABLE `tb_pembelian_detail` (
  `detail_id` int(11) NOT NULL,
  `detail_id_transaksi` int(11) NOT NULL,
  `detail_nama` varchar(50) NOT NULL,
  `detail_obat_id` double NOT NULL,
  `detail_satuan_beli` varchar(255) NOT NULL DEFAULT 'tes',
  `detail_harga_jual` double NOT NULL DEFAULT 0,
  `detail_harga_beli` double DEFAULT 0,
  `detail_diskon` int(11) NOT NULL DEFAULT 0,
  `detail_expired` date NOT NULL DEFAULT current_timestamp(),
  `detail_tanggal_terima` date NOT NULL DEFAULT current_timestamp(),
  `detail_jumlah` int(11) NOT NULL,
  `detail_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_pembelian_detail`
--

INSERT INTO `tb_pembelian_detail` (`detail_id`, `detail_id_transaksi`, `detail_nama`, `detail_obat_id`, `detail_satuan_beli`, `detail_harga_jual`, `detail_harga_beli`, `detail_diskon`, `detail_expired`, `detail_tanggal_terima`, `detail_jumlah`, `detail_harga`) VALUES
(11, 10, 'Ethambutol', 0, '0', 0, 0, 0, '2020-07-24', '2020-07-24', 50, 400000),
(12, 10, 'Paracetamol', 0, '0', 0, 0, 0, '2020-07-24', '2020-07-24', 100, 500000),
(13, 11, 'Ethambutol', 0, '0', 0, 0, 0, '2020-07-24', '2020-07-24', 70, 560000),
(14, 11, 'Paracetamol', 0, '0', 0, 0, 0, '2020-07-24', '2020-07-24', 70, 350000),
(15, 12, 'Ethambutol', 0, '0', 0, 0, 0, '2020-07-24', '2020-07-24', 4, 32000),
(16, 16, 'COUNTERPAIN 15 GR', 0, '43457', 5454, 0, 43, '2020-07-18', '2020-07-25', 3, 60000),
(23, 24, 'Paracetamol', 2, '0', 12, 12, 21, '2020-07-02', '2020-07-16', 21, 105000);

--
-- Trigger `tb_pembelian_detail`
--
DELIMITER $$
CREATE TRIGGER `update_stok_beli` AFTER INSERT ON `tb_pembelian_detail` FOR EACH ROW BEGIN
UPDATE tb_obat SET obat_stok = obat_stok + NEW.detail_jumlah WHERE obat_id = NEW.detail_obat_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembelian_temp`
--

CREATE TABLE `tb_pembelian_temp` (
  `temp_id` int(11) NOT NULL,
  `temp_faktur` varchar(255) NOT NULL,
  `temp_nama` varchar(50) NOT NULL,
  `temp_obat_id` varchar(255) NOT NULL,
  `temp_satuan_beli` double NOT NULL DEFAULT 0,
  `temp_harga_beli` double NOT NULL,
  `temp_harga_jual` double NOT NULL DEFAULT 0,
  `temp_diskon` int(11) NOT NULL DEFAULT 0,
  `temp_expired` date NOT NULL DEFAULT current_timestamp(),
  `temp_tanggal_terima` date NOT NULL DEFAULT current_timestamp(),
  `temp_jumlah` int(11) NOT NULL,
  `temp_totalharga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pengaturan`
--

CREATE TABLE `tb_pengaturan` (
  `id_pengaturan` int(36) NOT NULL,
  `nama_apotek` varchar(255) NOT NULL,
  `alamat_apotek` text NOT NULL,
  `notifikasi_expired` varchar(255) NOT NULL,
  `stok_minimal` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penjualan`
--

CREATE TABLE `tb_penjualan` (
  `penjualan_id` int(11) NOT NULL,
  `penjualan_id_transaksi` char(30) NOT NULL,
  `penjualan_tanggal` date NOT NULL,
  `penjualan_subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_penjualan`
--

INSERT INTO `tb_penjualan` (`penjualan_id`, `penjualan_id_transaksi`, `penjualan_tanggal`, `penjualan_subtotal`) VALUES
(12, 'PNJ-20200726225204', '2020-07-26', 0),
(13, 'PNJ-20200726225447', '2020-07-26', 0),
(14, 'PNJ-20200726225457', '2020-07-26', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penjualan_detail`
--

CREATE TABLE `tb_penjualan_detail` (
  `detail_id` int(11) NOT NULL,
  `detail_id_transaksi` int(11) NOT NULL,
  `detail_obat_id` varchar(255) NOT NULL DEFAULT '0',
  `detail_obat` varchar(50) NOT NULL,
  `detail_jumlah` int(11) NOT NULL,
  `detail_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_penjualan_detail`
--

INSERT INTO `tb_penjualan_detail` (`detail_id`, `detail_id_transaksi`, `detail_obat_id`, `detail_obat`, `detail_jumlah`, `detail_harga`) VALUES
(16, 12, '12', 'GRALYSIN 60 ML', 1, 12000),
(17, 13, '12', 'GRALYSIN 60 ML', 1, 12000);

--
-- Trigger `tb_penjualan_detail`
--
DELIMITER $$
CREATE TRIGGER `update_stok_jual` AFTER INSERT ON `tb_penjualan_detail` FOR EACH ROW BEGIN
UPDATE tb_obat SET obat_stok = obat_stok - NEW.detail_jumlah WHERE obat_id = NEW.detail_obat_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penjualan_temp`
--

CREATE TABLE `tb_penjualan_temp` (
  `temp_id` int(11) NOT NULL,
  `temp_obat_id` varchar(30) NOT NULL,
  `temp_nama` varchar(50) NOT NULL,
  `temp_jumlah` int(11) NOT NULL,
  `temp_totalharga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_supplier`
--

CREATE TABLE `tb_supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_nama` varchar(50) NOT NULL,
  `supplier_alamat` text NOT NULL,
  `supplier_telp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_supplier`
--

INSERT INTO `tb_supplier` (`supplier_id`, `supplier_nama`, `supplier_alamat`, `supplier_telp`) VALUES
(1, 'PT Andi', 'Jalan Kalpataru No. 43 Malang', '085847456294'),
(3, 'PT Maju Sejahtera', 'Jalan Sumbersar No.100', '08537375735'),
(4, 'PT. Sukses Selalu', 'Jalan Patimura No.30', '085123456789');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `user_nama` char(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `user_nama`, `user_name`, `user_password`, `user_foto`) VALUES
(1, 'Yahya dani Li', 'admin', '0192023a7bbd73250516f069df18b500', 'avatar6.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_obat`
--
ALTER TABLE `tb_obat`
  ADD PRIMARY KEY (`obat_id`);

--
-- Indeks untuk tabel `tb_pembelian`
--
ALTER TABLE `tb_pembelian`
  ADD PRIMARY KEY (`pembelian_id`);

--
-- Indeks untuk tabel `tb_pembelian_detail`
--
ALTER TABLE `tb_pembelian_detail`
  ADD PRIMARY KEY (`detail_id`);

--
-- Indeks untuk tabel `tb_pembelian_temp`
--
ALTER TABLE `tb_pembelian_temp`
  ADD PRIMARY KEY (`temp_id`);

--
-- Indeks untuk tabel `tb_pengaturan`
--
ALTER TABLE `tb_pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indeks untuk tabel `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  ADD PRIMARY KEY (`penjualan_id`);

--
-- Indeks untuk tabel `tb_penjualan_detail`
--
ALTER TABLE `tb_penjualan_detail`
  ADD PRIMARY KEY (`detail_id`);

--
-- Indeks untuk tabel `tb_penjualan_temp`
--
ALTER TABLE `tb_penjualan_temp`
  ADD PRIMARY KEY (`temp_id`);

--
-- Indeks untuk tabel `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_obat`
--
ALTER TABLE `tb_obat`
  MODIFY `obat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tb_pembelian`
--
ALTER TABLE `tb_pembelian`
  MODIFY `pembelian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `tb_pembelian_detail`
--
ALTER TABLE `tb_pembelian_detail`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `tb_pembelian_temp`
--
ALTER TABLE `tb_pembelian_temp`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `tb_pengaturan`
--
ALTER TABLE `tb_pengaturan`
  MODIFY `id_pengaturan` int(36) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  MODIFY `penjualan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tb_penjualan_detail`
--
ALTER TABLE `tb_penjualan_detail`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `tb_penjualan_temp`
--
ALTER TABLE `tb_penjualan_temp`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `tb_supplier`
--
ALTER TABLE `tb_supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
