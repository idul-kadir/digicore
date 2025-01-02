<?php
require 'function.php';

// for($i=0; $i<3; $i++){
//   $cari_pesan = query("SELECT * FROM `pesan` WHERE `status` = 'pending' ORDER BY RAND()");
//   if(mysqli_num_rows($cari_pesan)>0){
//     $data = mysqli_fetch_assoc($cari_pesan);
//     $tujuan = format_nomor($data['tujuan']);
//     $pesan = $data['pesan'];
//     $id = $data['id'];
//     if(!in_array($tujuan,$nomor_server)){
//       kirim($tujuan,$pesan);
//     }else{
//       query("UPDATE `pesan` SET `status`='gagal',`keterangan`='nomor tujuan merupakan nomor server' WHERE id = '$id' ");
//     }
//   }
// }

//mengecek konektifitas whatsapp
$menit = (int)date('i');
if($menit%15 == 0){
}
cek_koneksi();