<?php
require 'function.php';
$nip_lama = '19780205202411006';
$nip_baru = '197802052024211006';
$tabel = query("SHOW TABLES");
foreach($tabel as $data){
  $kumpulan_tabel[] = $data;
}
// var_dump($kumpulan_tabel);
// exit;
foreach($kumpulan_tabel as $data){
  $tabel = $data['Tables_in_test'];
  $cari = query("SELECT * FROM `$tabel`");
  foreach($cari as $data){
    
    foreach($data as $key => $val){
      $cari_data = query("SELECT * FROM `$tabel` WHERE `$key` = '$nip_lama' ");
      if(mysqli_num_rows($cari_data)>0){
        query("UPDATE `$tabel` SET `$key`='$nip_baru' WHERE `$key` = '$nip_lama' ");
      }
    }
  
  }
  
}
exit;
