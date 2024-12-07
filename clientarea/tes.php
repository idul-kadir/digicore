<?php
// require 'function.php';

// $ip = $_SERVER['IP_CHR'];
// $user = $_SERVER['USER_CHR'];
// $pass = $_SERVER['PASS_CHR'];
// $prefix = '192.168.50.1/24';

// buat();

// function buat(){
// // Membuat objek API
// $API = new RouterosAPI();

// // Menghubungkan ke MikroTik
// if ($API->connect($_SERVER['IP_CHR'], $_SERVER['USER_CHR'], $_SERVER['PASS_CHR'])) {

//  try {
//           // $API->comm('/ppp/secret/add', [
//           //   'name' => 'les',
//           //   'password' => 'les',
//           //   'service' => 'any',
//           //   'profile' => 'VPN-TUNNEL',
//           //   'comment' => '6 Desember 2024',
//           //   'remote-address' => '174.25.22.23'
//           // ]);
//           $list = $API->comm('/ip/firewall/nat/print');
//           print_r($list);
//         } catch (Exception $e) {
//           // Tangani error dengan mencatat atau memberi pesan kesalahan
//           $result = "Error: " . $e->getMessage();
//         }
//         $API->disconnect();
// } else {
//     echo "Gagal menghubungkan ke MikroTik";
// }

// }
$file = 'assets/konektor/DigiCore-Wireguard-1733406266.conf';
 if (file_exists($file)) {
    // Membaca file dan menyimpannya sebagai array
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   
    foreach($lines as $baris => $value){
      if(strpos('address',$value) !== false){
        echo 'ditemukan di baris '.$baris+1;
      }
    }
   
  } else {
    echo "File tidak ditemukan.";
  }
?>
