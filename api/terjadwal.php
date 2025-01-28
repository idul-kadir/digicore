<?php
require 'function.php';

$jam = date('H:i');

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
          $file = fopen("gagal.json","w+");
          fwrite($file,json_encode($result));
          fclose($file);
        }
      }
      query("UPDATE `pesan` SET `terkirim`='$eksekusi',`status`='$status',`keterangan`='$keterangan' WHERE id = '$id' ");
      sleep(17+$i);
    }
  }else{
    sleep(15);
  }
}

//mengecek konektifitas whatsapp setiap 15 menit
$menit = (int)date('i');

if($menit%30 == 0){
  $cek_spam = query("SELECT * FROM `pesan` WHERE `status` = 'spam' ORDER BY RAND() LIMIT 1");
  if(mysqli_num_rows($cek_spam)>0){
    $data = mysqli_fetch_assoc($cek_spam);
    $sekarang = time();
    $id = $data['id'];
    $selisih = $sekarang - $data['id'];
    if($selisih > 1200){
      query("UPDATE `pesan` SET `status`='pending' WHERE id = '$id' ");
    }
  }
}

if($menit%15 == 0){
  cek_koneksi();
  sleep(10);
  foreach($list_server as $server){
    $data = json_decode(file_get_contents("$server"), true);
    $status = $data['status'];
    if($status != 'connected'){
      if($status != 'server down'){
        $server = $data['categori'];
        $nomor = $data['number']; 
        $link_server = $data['link_server'].'app/login-with-code?phone='.$nomor;
        $pesan = "whatsapp di server $server. Silahkan kunjungi linknya di $link_server";
        kirim_pesan_telegram($pesan);
      }else{
        $pesan = "whatsapp di server $server down. Kunjungi linknya ".$data['link_server'];
        kirim_pesan_telegram($pesan);
      }
    }
  }
}

//h-1 habis layanan whatsapp
$layanan = ['l_whatsapp'];
if($jam == '20:02'){
  $besok = date('Y-m-d', strtotime("+1 days"));
  $cek_layanan = query("SELECT * FROM `l_whatsapp` WHERE tgl_expired = '$besok' ");
  if(mysqli_num_rows($cek_layanan)>0){
    while($data = mysqli_fetch_assoc($cek_layanan)){
      $id_user = $data['id_user'];
      $data_user = detail_user($id_user);
      $data_produk = produk($data['kode_produk']);
      if($data_user != false){
        $tgl_batas = tgl_indo($besok);
        $nama_user = $data_user['nama'];
        $harga = rupiah($data_produk['harga']);
        $tgl_expired = tgl_indo($besok);
        $pesan = "Halo, *$nama_user*.\n\n" .
         "Kami ingin mengingatkan bahwa layanan notifikasi whatsapp disistem Anda akan berakhir *hari ini*. Untuk memastikan layanan tetap aktif tanpa gangguan, kami sarankan Anda segera melakukan perpanjangan.\n\n" .
         "--------------------------------------------------\n" .
         "*Detail Pembayaran:*\n" .
         "- *Jumlah yang harus dibayar*: $harga\n" .
         "- *Metode pembayaran*:\n" .
         " - BCA 7975-4374-26 a/n RIDWAN KADIR\n".
         " - JAGO 1038-9838-0445 a/n RIDWAN KADIR\n".
         "--------------------------------------------------\n\n" .
         "Segera lakukan pembayaran sebelum tanggal *$tgl_expired* untuk menghindari penonaktifan layanan. Jika Anda sudah melakukan pembayaran, mohon abaikan pesan ini.\n\n" .
         "Jika ada pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi kami di:\n" .
         "- WhatsApp: 089669106718\n" .
         "- Email: layanan@digicore.web.id\n\n" .
         "--------------------------------------------------\n" .
         "Terima kasih atas kepercayaan Anda. 😊\n\n" .
         "*Hormat kami,*\n" .
         "DigiCore";
        antrian($pesan,$data_user['wa'],$data['id']);
      }
    }
  }
}

if($jam == '00:02'){
  $hari_ini = date('Y-m-d');
  $cek_layanan = query("SELECT * FROM `l_whatsapp` WHERE tgl_expired < '$hari_ini' AND `status` = 'aktif' ");
  if(mysqli_num_rows($cek_layanan)>0){
    while($data = mysqli_fetch_assoc($cek_layanan)){
      $id_user = $data['id_user'];
      $data_user = detail_user($id_user);
      $data_produk = produk($data['kode_produk']);
      $saldo = $data_user['saldo'];
      $biaya = $data_produk['harga'];
      $nama_user = $data_user['nama'];
      $perpanjang = strtolower($data['perpanjang']);
      $kode_produk = $data['kode_produk'];
      $id_layanan = $data['id'];
      if($perpanjang == 'ya'){
        if($saldo < $biaya){
          $biaya_layanan = rupiah($biaya);
          $pesan = "*EXPIRED*\n\nKepada yth, *$nama_user* dengan berat hati layanan whatsapp notifikasi dengan kode produk $kode_produk dinonaktifkan karena saldo anda tidak mencukupi untuk memperpanjang biaya layanan sebesar *$biaya_layanan* \n\n" . 
          "Jika ada pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi kami di:\n" .
          "--------------------------------------------------\n" .
          "- WhatsApp: 089669106718\n" .
          "- Email: layanan@digicore.web.id\n\n" .
          "--------------------------------------------------\n" .
          "Hormat kami,\n*DigiCore*";
        antrian($pesan,$data_user['wa'],$data['id']);
        query("UPDATE `l_whatsapp` SET `status`='expired' WHERE id = '$id_layanan' ");
        }
      }else{
        $pesan = "*EXPIRED*\n\nKepada yth, *$nama_user* layanan whatsapp notifikasi dengan kode produk $kode_produk dinonaktifkan karena fitur perpanjang otomatis dalam keadaan _*non aktif*_ \n\nSalam,\n*DigiCore*";
        antrian($pesan,$data_user['wa'],$data['id']);
        query("UPDATE `l_whatsapp` SET `status`='expired' WHERE id = '$id_layanan' ");
      }
    }
  }
}