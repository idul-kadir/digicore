<?php
require 'function.php';

for($i=0; $i<3; $i++){  
  $cari_pesan = query("SELECT * FROM `pesan` WHERE `status` = 'pending' ORDER BY RAND() LIMIT 1");
  if(mysqli_num_rows($cari_pesan)>0){
    $data = mysqli_fetch_assoc($cari_pesan);
    $tujuan = $data['tujuan'];
    $pesan = $data['pesan'];
    $id = $data['id'];
    $eksekusi = time();
    $hasil = kirim_pesan($pesan,$tujuan);
    if($hasil != false){
      $result = json_decode($hasil, true);
      $server = $result['server'];
      $status = $result['result']['code'];
      $keterangan = $result['result']['message'];
      if($status == 'SUCCESS'){
        $status = 'sukses';
        $keterangan = $server;
      }else{
        $status = 'gagal';
      }
      query("UPDATE `pesan` SET `terkirim`='$eksekusi',`status`='$status',`keterangan`='$keterangan' WHERE id = '$id' ");
    }
    sleep(20);
  }else{
    echo 'tidak ada pesan';
  }
}

//mengecek konektifitas whatsapp
$menit = (int)date('i');
if($menit%15 == 0){
  // cek_koneksi();
  echo '<br>mengecek koneksi juga';
}