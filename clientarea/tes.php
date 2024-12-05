<?php
require 'function.php';

$ip = $_SERVER['IP_CHR'];
$user = $_SERVER['USER_CHR'];
$pass = $_SERVER['PASS_CHR'];
$prefix = '192.168.50.1/24';
// Membuat objek API
$API = new RouterosAPI();

// Menghubungkan ke MikroTik
if ($API->connect($ip, $user, $pass)) {

  $list = $API->comm("/interface/wireguard/peers/print");

  echo json_encode($list);

    // Putuskan koneksi setelah semua perintah selesai
    $API->disconnect();
} else {
    echo "Gagal menghubungkan ke MikroTik";
}
?>
