-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Des 2024 pada 00.36
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
  `config` varchar(200) NOT NULL,
  `status` set('aktif','terpakai','tidak aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `connector`
--

INSERT INTO `connector` (`id`, `jenis`, `ip`, `config`, `status`) VALUES
(1733058914, 'Wireguard', '10.66.66.100', 'assets/konektor/DigiCore-Wireguard-1733058914.conf', 'tidak aktif'),
(1733059060, 'Wireguard', '10.66.66.101', 'assets/konektor/DigiCore-Wireguard-1733059060.conf', 'tidak aktif'),
(1733059157, 'Wireguard', '10.66.66.102', 'assets/konektor/DigiCore-Wireguard-1733059157.conf', 'tidak aktif'),
(1733059174, 'Wireguard', '10.66.66.103', 'assets/konektor/DigiCore-Wireguard-1733059174.conf', 'tidak aktif'),
(1733059197, 'Wireguard', '10.66.66.104', 'assets/konektor/DigiCore-Wireguard-1733059197.conf', 'tidak aktif'),
(1733059215, 'Wireguard', '10.66.66.105', 'assets/konektor/DigiCore-Wireguard-1733059215.conf', 'tidak aktif'),
(1733059233, 'Wireguard', '10.66.66.106', 'assets/konektor/DigiCore-Wireguard-1733059233.conf', 'tidak aktif'),
(1733059256, 'Wireguard', '10.66.66.107', 'assets/konektor/DigiCore-Wireguard-1733059256.conf', 'tidak aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `l_vpn`
--

CREATE TABLE `l_vpn` (
  `id` int(11) NOT NULL,
  `kode_produk` varchar(30) NOT NULL,
  `id_user` varchar(20) NOT NULL,
  `tgl_expired` date NOT NULL,
  `perpanjang` enum('ya','tidak') NOT NULL,
  `status` set('aktif','tidak aktif','expired') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('BISNIS', 'BISNIS', 'Whatsapp', 70000, '[\"Berlaku 1 bulan\",\"Unlimited pesan\",\"Nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Monitoring pesan\",\"Nomor pengirim acak\",\"Perpanjang otomatis\",\"Dukungan tim teknis\"]', '[\"Webhook\"]', 'aktif'),
('FREE', 'GRATIS', 'Whatsapp', 0, '[\"350 pesan perbulan\",\"nomor disediakan oleh digicore\",\"Adanya watermark dari digicore\",\"didukung Rest API\",\"Nomor pengirim acak\",\"Monitoring pesan\",\"Dukungan tim teknik terbatas\"]', '[\"Webhook\",\"perpanjang otomatis\"]', 'aktif'),
('IND 1', 'IND 1', 'VPS', 65000, '[\"CPU 1 Core\",\"RAM 1 GB\",\"Storage 25 GB\",\"Location Indonesia\",\"Bandwith Unlimited\",\"Lokal up to 10 Gbps\",\"300 Mbps Internasional\"]', '[\"VPN Tunneling\",\"Pengalihan Trafik\"]', 'aktif'),
('IP USA 1', 'IP USA 1', 'IP Public', 200000, '[\"IP Location USA\",\"Bandwith 0,5TB\",\"Berlaku 3 bulan\",\"Latency medium\"]', '[\"\"]', 'aktif'),
('MANDIRI', 'MANDIRI', 'Whatsapp', 50000, '[\"Berlaku 1 bulan\",\"Unlimited pesan\",\"nomor disediakan oleh client\",\"Didukung Rest API\",\"Monitoring pesan\",\"Notifikasi koneksi\",\"Perpanjang otomatis\",\"Dukungan tim teknis\"]', '[\"Webhook\"]', 'aktif'),
('OTP', 'OTP', 'Whatsapp', 25000, '[\"750 pesan lifetime\",\"Maksimal 100 karakter\",\"nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Nomor pengirim acak\",\"Monitoring pesan\",\"Notifikasi limit\",\"Perpanjang otomatis\",\"Dukungan tim teknis\"]', '[\"Webhook\"]', 'aktif'),
('SEMPURNA', 'SEMPURNA', 'Whatsapp', 120000, '[\"Berlaku 1 bulan\",\"Unlimited pesan\",\"Nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Monitoring pesan\",\"Nomor pengirim tetap\",\"Perpanjang otomatis\",\"Dukungan tim teknis penuh\",\"Nomor dedicated\",\"Webhook\"]', '[\"\"]', 'aktif'),
('SPONSORSHIP', 'SPONSORSHIP', 'Whatsapp', 150000, '[\"Berlaku sesuai kesepakatan\",\"Unlimited pesan\",\"Nomor disediakan oleh DigiCore\",\"Didukung Rest API\",\"Monitoring pesan\",\"Nomor pengirim tetap\",\"Perpanjang otomatis\",\"Dukungan tim teknis penuh\",\"Nomor dedicated\",\"Webhook\"]', '[\"\"]', 'aktif'),
('TUNNEL 1', 'TUNNEL 1', 'VPN Tunnel', 5000, '[\"1 Port\",\"Masa aktif 1 bulan\",\"Unlimited Bandwith\",\"Unlimited Kuota\",\"VPN Wireguard\",\" VPN Openvpn\",\"Server USA\",\"Protocol TCP\"]', '[\"\"]', 'aktif'),
('TUNNEL 3', 'TUNNEL 3', 'VPN Tunnel', 10000, '[\"3 Port\",\" Masa aktif 1 bulan\",\"Unlimited Bandwith\",\" Unlimited Kuota\",\"VPN Wireguard\",\" VPN Openvpn\",\"Server USA\",\" Protokol TCP\"]', '[\"\"]', 'aktif'),
('TUNNEL 80', 'TUNNEL WEB SERVER', 'VPN Tunnel', 10000, '[\"Port 80\",\"Masa aktif 1 bulan\",\"Bandwith 5Mbps Dedicated\",\"Maksimal 2 domain\",\" GRATIS SSL\",\"Gratis 2 sub-domain DigiCore\",\"Dukungan Tim Teknis\"]', '[\"\"]', 'aktif'),
('USA 1', 'USA', 'VPS', 50000, '[\"CPU 1 core\",\"RAM 512 MB\",\"Storage 15 GB\",\"Bandwith 0.5 TB\",\"Location USA\",\"Harga bulanan\"]', '[\"\"]', 'aktif'),
('USA 2', 'USA 2', 'VPS', 69500, '[\"CPU 1 Core\",\"RAM 1 GB\",\" Storage 25 GB\",\"Bandwith 1TB\",\"Location USA\"]', '[\"\"]', 'aktif');

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
(1732487217, 'Ridwan Kadir', '089669106718', '865a4f84143b1e26f0289fae728a62e9', 20000, '');

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
