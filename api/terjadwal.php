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
        if($keterangan == 'you are not logged in'){
          $status = 'pending';
          $keterangan = '';
          cek_koneksi();
        }else{
          $status = 'gagal';
        }
      }
      query("UPDATE `pesan` SET `terkirim`='$eksekusi',`status`='$status',`keterangan`='$keterangan' WHERE id = '$id' ");
    }
    sleep(17);
  }else{
    sleep(15);
  }
}

//mengecek apakah pesan kategori spam sudah bisa dikirim
$cek_spam = query("SELECT * FROM `pesan` WHERE `status` = 'spam' ORDER BY id ASC LIMIT 2 ");
if(mysqli_num_rows($cek_spam) > 0){
  while($data = mysqli_fetch_assoc($cek_spam)){
    $w_masuk = $data['id'];
    $w_sekarang = time();
    if($w_sekarang - $w_masuk >= 1800){
      query("UPDATE `pesan` SET `status` = 'pending' WHERE id = '$w_masuk' ");
    }
  }
}

//mengecek konektifitas whatsapp setiap 5 menit
$menit = (int)date('i');
if($menit%15 == 0){
  foreach($list_server as $server){
    $data = file_get_contents($server);
    $status[] = json_decode($data, true);
  }
  cek_koneksi();
  sleep(2);

  foreach($list_server as $server){
    $link = $server['link_server'];
    $number = $server['number'];
    $status_s = $server['status'];
    $kategori = $server['categori'];
    for($i=0; $i<count($status); $i++){
      if($kategori == $status[$i]['categori'] AND $link == $status[$i]['link']){
        if($status_s != $status[$i]['status']){
          $pesan = "whatsapp $kategori $status_s. Silahkan kunjungi $link";
          kirim_pesan_telegram($pesan);
        }
      }
    }

  }
}