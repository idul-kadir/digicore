<?php
// Lokasi file konfigurasi WireGuard
$configFile = 'assets/konektor/DigiCore-Wireguard-1733755007.conf';

// Periksa apakah file ada
if (!file_exists($configFile)) {
    die("File konfigurasi tidak ditemukan.");
}

// Baca isi file konfigurasi
$configContent = file_get_contents($configFile);

// URL API QR Code
$apiUrl = "https://api.qrserver.com/v1/create-qr-code/";

// Query parameter
$queryParams = http_build_query([
    'size' => '300x300', // Ukuran QR Code
    'data' => $configContent, // Isi konfigurasi WireGuard
]);

// URL lengkap
$qrCodeUrl = $apiUrl . '?' . $queryParams;

// Tampilkan QR Code langsung di browser
header('Content-Type: image/png');
echo file_get_contents($qrCodeUrl);
?>
