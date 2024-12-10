<?php
require 'function.php';

// $ip = $_SERVER['IP_CHR'];
// $user = $_SERVER['USER_CHR'];
// $pass = $_SERVER['PASS_CHR'];
// $prefix = '192.168.50.1/24';

// buat();

// function buat(){
// // Membuat objek API
$API = new RouterosAPI();

$file = 'assets/konektor/server.conf';
$line = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$baris = 0;
foreach($line as $data){
  if(strpos($data, '[Peer]') !== false){
    $pub_lickey = explode(' = ',$line[$baris+1]);
    $pre_shared = explode(' = ', $line[$baris+2]);
    $all_owed = explode(' = ', $line[$baris+3]);

    // // Menghubungkan ke MikroTik
    if ($API->connect($_SERVER['IP_CHR'], $_SERVER['USER_CHR'], $_SERVER['PASS_CHR'])) {
    // Menambahkan peer WireGuard
      $result = $API->comm('/interface/wireguard/peers/add', array(
          'interface' => 'wg-server1',
          'public-key' => $pub_lickey[1],
          'preshared-key' => $pre_shared[1],
          'allowed-address' => $all_owed[1],
          'comment' => 'Peer untuk user DigiCore'
      ));
    
      if (isset($result['!trap'])) {
          // Menangani error jika terjadi
          echo "Error: " . $result['!trap'][0]['message'];
      } else {
          echo "Peer WireGuard berhasil ditambahkan!";
      }
    
      $API->disconnect();

    } else {
        echo "Gagal terhubung ke router.";
    }

  }
  $baris++;
}

?>
