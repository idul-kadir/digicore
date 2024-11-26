-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Nov 2024 pada 11.29
-- Versi server: 10.4.32-MariaDB-log
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digicore`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `kode` varchar(35) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `kategori` varchar(25) NOT NULL,
  `harga` int(11) NOT NULL,
  `sk_terima` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sk_terima`)),
  `sk_tolak` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sk_tolak`)),
  `status` set('aktif','tidak aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`kode`, `nama`, `kategori`, `harga`, `sk_terima`, `sk_tolak`, `status`) VALUES
('FREE', 'GRATIS', 'Whatsapp', 0, '[\"350 pesan perbulan\",\"nomor disediakan oleh digicore\",\"Adanya watermark dari digicore\",\"didukung Rest API\",\"Nomor pengirim acak\",\"Monitoring pesan\",\"Dukungan tim teknik terbatas\"]', '[\"Webhook\",\"perpanjang otomatis\"]', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `wa` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `saldo` int(11) NOT NULL,
  `aktifitas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `wa`, `password`, `saldo`, `aktifitas`) VALUES
(1732487217, 'Ridwan Kadir', '089669106718', '865a4f84143b1e26f0289fae728a62e9', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`kode`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
