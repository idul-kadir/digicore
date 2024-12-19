-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Des 2024 pada 00.51
-- Versi server: 10.4.32-MariaDB-log
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `connector`
--

CREATE TABLE `connector` (
  `id` int(11) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `catatan` varchar(20) NOT NULL,
  `config` varchar(200) NOT NULL,
  `status` set('aktif','terpakai','tidak aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `connector`
--

INSERT INTO `connector` (`id`, `jenis`, `ip`, `catatan`, `config`, `status`) VALUES
(1733521774, 'ANY', '128.44.56.2', '6694', '', 'aktif'),
(1733522024, 'OVPN', '128.44.56.3', '3594', '', 'aktif'),
(1733522042, 'OVPN', '128.44.56.4', '8159', '', 'tidak aktif'),
(1733522053, 'ANY', '128.44.56.5', '5142', '', 'tidak aktif'),
(1733522065, 'ANY', '128.44.56.6', '6252', '', 'tidak aktif'),
(1733522079, 'ANY', '128.44.56.7', '8082', '', 'tidak aktif'),
(1733522092, 'ANY', '128.44.56.8', '9691', '', 'tidak aktif'),
(1733754322, 'Wireguard', '10.70.0.2', '', 'assets/konektor/DigiCore-Wireguard-1733754322.conf', 'aktif'),
(1733754341, 'Wireguard', '10.70.0.3', '', 'assets/konektor/DigiCore-Wireguard-1733754341.conf', 'aktif'),
(1733754359, 'Wireguard', '10.70.0.4', '', 'assets/konektor/DigiCore-Wireguard-1733754359.conf', 'tidak aktif'),
(1733754376, 'Wireguard', '10.70.0.5', '', 'assets/konektor/DigiCore-Wireguard-1733754376.conf', 'tidak aktif'),
(1733754392, 'Wireguard', '10.70.0.6', '', 'assets/konektor/DigiCore-Wireguard-1733754392.conf', 'tidak aktif'),
(1733754436, 'Wireguard', '10.70.0.7', '', 'assets/konektor/DigiCore-Wireguard-1733754436.conf', 'tidak aktif'),
(1733754505, 'Wireguard', '10.70.0.10', '', 'assets/konektor/DigiCore-Wireguard-1733754505.conf', 'tidak aktif'),
(1733754534, 'Wireguard', '10.70.0.12', '', 'assets/konektor/DigiCore-Wireguard-1733754534.conf', 'tidak aktif'),
(1733754573, 'Wireguard', '10.70.0.9', '', 'assets/konektor/DigiCore-Wireguard-1733754573.conf', 'tidak aktif'),
(1733754605, 'Wireguard', '10.70.0.11', '', 'assets/konektor/DigiCore-Wireguard-1733754605.conf', 'tidak aktif'),
(1733754641, 'Wireguard', '10.70.0.13', '', 'assets/konektor/DigiCore-Wireguard-1733754641.conf', 'tidak aktif'),
(1733754668, 'Wireguard', '10.70.0.14', '', 'assets/konektor/DigiCore-Wireguard-1733754668.conf', 'tidak aktif'),
(1733754726, 'Wireguard', '10.70.0.8', '', 'assets/konektor/DigiCore-Wireguard-1733754726.conf', 'tidak aktif'),
(1733754765, 'Wireguard', '10.70.0.15', '', 'assets/konektor/DigiCore-Wireguard-1733754765.conf', 'tidak aktif'),
(1733754782, 'Wireguard', '10.70.0.16', '', 'assets/konektor/DigiCore-Wireguard-1733754782.conf', 'tidak aktif'),
(1733754800, 'Wireguard', '10.70.0.17', '', 'assets/konektor/DigiCore-Wireguard-1733754800.conf', 'tidak aktif'),
(1733754844, 'Wireguard', '10.70.0.18', '', 'assets/konektor/DigiCore-Wireguard-1733754844.conf', 'tidak aktif'),
(1733754868, 'Wireguard', '10.70.0.19', '', 'assets/konektor/DigiCore-Wireguard-1733754868.conf', 'tidak aktif'),
(1733754886, 'Wireguard', '10.70.0.20', '', 'assets/konektor/DigiCore-Wireguard-1733754886.conf', 'tidak aktif'),
(1733754903, 'Wireguard', '10.70.0.21', '', 'assets/konektor/DigiCore-Wireguard-1733754903.conf', 'tidak aktif'),
(1733754933, 'Wireguard', '10.70.0.22', '', 'assets/konektor/DigiCore-Wireguard-1733754933.conf', 'tidak aktif'),
(1733754952, 'Wireguard', '10.70.0.23', '', 'assets/konektor/DigiCore-Wireguard-1733754952.conf', 'tidak aktif'),
(1733754970, 'Wireguard', '10.70.0.24', '', 'assets/konektor/DigiCore-Wireguard-1733754970.conf', 'tidak aktif'),
(1733754988, 'Wireguard', '10.70.0.25', '', 'assets/konektor/DigiCore-Wireguard-1733754988.conf', 'tidak aktif'),
(1733755007, 'Wireguard', '10.70.0.26', '', 'assets/konektor/DigiCore-Wireguard-1733755007.conf', 'tidak aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `l_vpn`
--

CREATE TABLE `l_vpn` (
  `id` int(11) NOT NULL,
  `kode_produk` varchar(30) NOT NULL,
  `id_user` varchar(20) NOT NULL,
  `konektor1` varchar(15) NOT NULL,
  `konektor2` varchar(15) NOT NULL,
  `tgl_expired` date NOT NULL,
  `perpanjang` enum('ya','tidak') NOT NULL,
  `status` set('aktif','tidak aktif','expired') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `l_vpn`
--

INSERT INTO `l_vpn` (`id`, `kode_produk`, `id_user`, `konektor1`, `konektor2`, `tgl_expired`, `perpanjang`, `status`) VALUES
(1734139316, 'TUNNEL 3', '1732487217', '1733754322', '1733521774', '2025-01-13', 'ya', 'aktif'),
(1734139358, 'TUNNEL 80', '1732487217', '1733754341', '1733522024', '2025-01-13', 'ya', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `l_whatsapp`
--

CREATE TABLE `l_whatsapp` (
  `id` int(11) NOT NULL,
  `kode_produk` varchar(30) NOT NULL,
  `id_user` varchar(20) NOT NULL,
  `apikey` varchar(255) NOT NULL,
  `tgl_expired` date NOT NULL,
  `perpanjang` enum('ya','tidak') NOT NULL,
  `status` set('aktif','tidak aktif','expired') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `l_whatsapp`
--

INSERT INTO `l_whatsapp` (`id`, `kode_produk`, `id_user`, `apikey`, `tgl_expired`, `perpanjang`, `status`) VALUES
(1732936464, 'FREE', '1732487217', 'xsoLgJZPpAkHoh1RRl8m0F3N8s2X0ibU1732936464919', '2024-12-30', 'tidak', 'aktif'),
(1732972064, 'OTP', '1732487217', 'KGVna6d4qKLfxbwgdqQBUtkbqhbAk1Nm1732972064834', '2024-12-30', 'tidak', 'aktif'),
(1732972515, 'OTP', '1732487217', '2rjyO0Dy5CP65GWd8kegGoc4KXqMgWZB1732972515375', '2024-12-30', 'ya', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id` int(11) NOT NULL,
  `tujuan` varchar(20) NOT NULL,
  `pesan` text NOT NULL,
  `id_produk` varchar(50) NOT NULL,
  `terkirim` int(11) NOT NULL,
  `status` enum('pending','sukses','gagal') NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `masa_berlaku` int(11) NOT NULL,
  `status` set('aktif','tidak aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`kode`, `nama`, `kategori`, `harga`, `sk_terima`, `sk_tolak`, `masa_berlaku`, `status`) VALUES
('BISNIS', 'BISNIS', 'Whatsapp', 70000, '[\"Berlaku 1 bulan\",\"Unlimited pesan\",\"Nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Monitoring pesan\",\"Nomor pengirim acak\",\"Perpanjang otomatis\",\"Dukungan tim teknis\"]', '[\"Webhook\"]', 30, 'aktif'),
('FREE', 'GRATIS', 'Whatsapp', 0, '[\"350 pesan perbulan\",\"nomor disediakan oleh digicore\",\"Adanya watermark dari digicore\",\"didukung Rest API\",\"Nomor pengirim acak\",\"Monitoring pesan\",\"Dukungan tim teknik terbatas\"]', '[\"Webhook\",\"perpanjang otomatis\"]', 30, 'aktif'),
('IND 1', 'IND 1', 'VPS', 65000, '[\"CPU 1 Core\",\"RAM 1 GB\",\"Storage 25 GB\",\"Location Indonesia\",\"Bandwith Unlimited\",\"Lokal up to 10 Gbps\",\"300 Mbps Internasional\"]', '[\"VPN Tunneling\",\"Pengalihan Trafik\"]', 30, 'aktif'),
('IP USA 1', 'IP USA 1', 'IP Public', 200000, '[\"IP Location USA\",\"Bandwith 0,5TB\",\"Berlaku 3 bulan\",\"Latency medium\"]', '[\"\"]', 90, 'aktif'),
('MANDIRI', 'MANDIRI', 'Whatsapp', 50000, '[\"Berlaku 1 bulan\",\"Unlimited pesan\",\"nomor disediakan oleh client\",\"Didukung Rest API\",\"Monitoring pesan\",\"Notifikasi koneksi\",\"Perpanjang otomatis\",\"Dukungan tim teknis\"]', '[\"Webhook\"]', 30, 'aktif'),
('OTP', 'OTP', 'Whatsapp', 25000, '[\"750 pesan lifetime\",\"Maksimal 100 karakter\",\"nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Nomor pengirim acak\",\"Monitoring pesan\",\"Notifikasi limit\",\"Perpanjang otomatis\",\"Dukungan tim teknis\"]', '[\"Webhook\"]', 30, 'aktif'),
('SEMPURNA', 'SEMPURNA', 'Whatsapp', 120000, '[\"Berlaku 1 bulan\",\"Unlimited pesan\",\"Nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Monitoring pesan\",\"Nomor pengirim tetap\",\"Perpanjang otomatis\",\"Dukungan tim teknis penuh\",\"Nomor dedicated\",\"Webhook\"]', '[\"\"]', 30, 'aktif'),
('SPONSORSHIP', 'SPONSORSHIP', 'Whatsapp', 150000, '[\"Berlaku sesuai kesepakatan\",\"Unlimited pesan\",\"Nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Monitoring pesan\",\"Nomor pengirim tetap\",\"Perpanjang otomatis\",\"Dukungan tim teknis penuh\",\"Nomor dedicated\",\"Webhook\"]', '[\"\"]', 30, 'aktif'),
('TUNNEL 1', 'TUNNEL 1', 'VPN Tunnel', 5000, '[\"1 Port\",\"Masa aktif 1 bulan\",\"Unlimited Bandwith\",\"Unlimited Kuota\",\"VPN Wireguard\",\" VPN Openvpn\",\"Server USA\",\"Protocol TCP\"]', '[\"\"]', 30, 'aktif'),
('TUNNEL 3', 'TUNNEL 3', 'VPN Tunnel', 7000, '[\"3 Port\",\" Masa aktif 1 bulan\",\"Unlimited Bandwith\",\" Unlimited Kuota\",\"VPN Wireguard\",\" VPN Openvpn\",\"Server USA\",\" Protokol TCP\"]', '[\"\"]', 30, 'aktif'),
('TUNNEL 80', 'TUNNEL WEB SERVER', 'VPN Tunnel', 10000, '[\"Port 80\",\"Masa aktif 1 bulan\",\"Bandwith 5Mbps Dedicated\",\"Maksimal 2 domain\",\" GRATIS SSL\",\"Gratis 2 sub-domain DigiCore\",\"Dukungan Tim Teknis\"]', '[\"\"]', 30, 'aktif'),
('USA 1', 'USA', 'VPS', 50000, '[\"CPU 1 core\",\"RAM 512 MB\",\"Storage 15 GB\",\"Bandwith 0.5 TB\",\"Location USA\",\"Harga bulanan\"]', '[\"\"]', 30, 'aktif'),
('USA 2', 'USA 2', 'VPS', 69500, '[\"CPU 1 Core\",\"RAM 1 GB\",\" Storage 25 GB\",\"Bandwith 1TB\",\"Location USA\"]', '[\"\"]', 30, 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tunnel`
--

CREATE TABLE `tunnel` (
  `id` int(11) NOT NULL,
  `id_vpn` varchar(20) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `src_port` varchar(10) NOT NULL,
  `dst_port` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tunnel`
--

INSERT INTO `tunnel` (`id`, `id_vpn`, `ip`, `src_port`, `dst_port`) VALUES
(22, '1734139316', '128.44.56.2', '4523', '22'),
(23, '1734139316', '128.44.56.2', '4716', '8291');

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
(1732487217, 'Ridwan Kadir', '089669106718', '865a4f84143b1e26f0289fae728a62e9', 39000, '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `connector`
--
ALTER TABLE `connector`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `l_vpn`
--
ALTER TABLE `l_vpn`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `l_whatsapp`
--
ALTER TABLE `l_whatsapp`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`kode`);

--
-- Indeks untuk tabel `tunnel`
--
ALTER TABLE `tunnel`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tunnel`
--
ALTER TABLE `tunnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
