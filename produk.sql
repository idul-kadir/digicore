-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Nov 2024 pada 09.51
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
