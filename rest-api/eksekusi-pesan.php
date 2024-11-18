<?php
require 'fungsi.php';

for($i=0; $i<4; $i++){
  $cari_pesan = query("SELECT * FROM `message` WHERE `status` = 'pending' OR `status` = '' ORDER BY waktu ASC LIMIT 1 ");
  $data = mysqli_fetch_assoc($cari_pesan);
  $pesan = $data['pesan'];
  $tujuan = $data['tujuan'];
  $waktu = $data['waktu'];
  $sesi = $data['sesi'];
  $id = $data['id'];

  if($sesi == 'all'){
    if(cekNomor($tujuan) == 'server1'){
      $result = json_decode(kirim_pesan($tujuan,$pesan,'server1'), true);
      if(isset($result['success'])){
        query("UPDATE `message` SET `status`='sukses',`keterangan`='Pesan berhasil dikirim',`sesi_pengirim`='server1' WHERE id = '$id' ");
      }else{
        $result = json_decode(kirim_pesan($tujuan,$pesan,'server2'), true);
          if(isset($result['success'])){
            query("UPDATE `message` SET `status`='sukses',`keterangan`='Pesan berhasil dikirim',`sesi_pengirim`='server2' WHERE id = '$id' ");
          }else{
            $result = json_decode(kirim_pesan($tujuan,$pesan,'server3'), true);
            if(isset($result['success'])){
              query("UPDATE `message` SET `status`='sukses',`keterangan`='Pesan berhasil dikirim',`sesi_pengirim`='server3' WHERE id = '$id' ");
            } 
          } 
      }
    }else{
      $result = json_decode(kirim_pesan($tujuan,$pesan,'server2'), true);
      if(isset($result['success'])){
        query("UPDATE `message` SET `status`='sukses',`keterangan`='Pesan berhasil dikirim',`sesi_pengirim`='server2' WHERE id = '$id' ");
      }else{
        $result = json_decode(kirim_pesan($tujuan,$pesan,'server1'), true);
          if(isset($result['success'])){
            query("UPDATE `message` SET `status`='sukses',`keterangan`='Pesan berhasil dikirim',`sesi_pengirim`='server1' WHERE id = '$id' ");
          }else{
            $result = json_decode(kirim_pesan($tujuan,$pesan,'server3'), true);
            if(isset($result['success'])){
              query("UPDATE `message` SET `status`='sukses',`keterangan`='Pesan berhasil dikirim',`sesi_pengirim`='server3' WHERE id = '$id' ");
            }
          } 
      }
    }
  }
  sleep(20);
}
