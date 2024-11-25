<?php
require 'fungsi.php';

header("Content-type:application/json");
$data = json_decode(file_get_contents("php://input"), true);

$file = fopen("pesan.json","w+");
  fwrite($file,json_encode($data));
  fclose($file);

if($data['category'] == 'status'){
  $server = $data['route'];
  if(file_exists("$server.json")){
    $result = json_decode(file_get_contents("$server.json"), true);
    $status_s = $result['status'];
    if($data['status'] == $status_s){
      if(time() - $data['timestamp'] > 600){
        $status_koneksi = $data['status'];
        $pesan_temp = "Koneksi $server status $status_koneksi";
        file_get_contents('https://api.telegram.org/bot1839436412:AAE_MDhhKuETRSksda3K9MqjWOW6RDLj348/sendMessage?chat_id=652919139&text='.$pesan_temp);
        //simpan data yang baru
        $pesan = ["category" => $data['category'], "status" => $status_koneksi, "timestamp" => time(), "route" => $server];
        
        $file = fopen("$server.json","w+");
        fwrite($file,json_encode($pesan));
        fclose($file);
      }
    }else{
      $status_koneksi = $data['status'];
      $pesan_temp = "Koneksi $server status $status_koneksi";
      file_get_contents('https://api.telegram.org/bot1839436412:AAE_MDhhKuETRSksda3K9MqjWOW6RDLj348/sendMessage?chat_id=652919139&text='.$pesan_temp);
      //simpan data yang baru
      $pesan = ["category" => $data['category'], "status" => $status_koneksi, "timestamp" => time(), "route" => $server];
      
      $file = fopen("$server.json","w+");
      fwrite($file,json_encode($pesan));
      fclose($file);
    }
  }else{
    $server = $data['route'];
    $file = fopen("$server.json","w+");
    fwrite($file,json_encode($data));
    fclose($file);
  }
}elseif($data['category'] == 'periodic_status'){
  $status = $data['status'];
  $route = $data['route'];
  $respon = ["category" => "Status Server", "status" => $status, "waktu" =>time(), "route" => $route];
  $file = fopen("$route.json","w+");
  fwrite($file,json_encode($respon));
  fclose($file);
}
else{
  $file = fopen("pesan.json","w+");
  fwrite($file,json_encode($data));
  fclose($file);
}



$pesan = "🌐 DIGICORE - Solusi Digital Terbaik untuk Bisnis Anda! 🌐\n\nHai, DigiCores! 🙋‍♂️\n\nApakah Anda sedang mencari solusi digital lengkap untuk mendukung perkembangan bisnis Anda? Digicore.web.id siap membantu dengan layanan yang lengkap dan terpercaya! 🚀\n\n🔹 WhatsApp Gateway\nMudahkan komunikasi bisnis Anda dengan pelanggan melalui API WhatsApp yang stabil dan mudah diintegrasikan.\n\n🔹 VPN Tunnel\nPastikan keamanan koneksi Anda dengan VPN Tunnel dari kami, cocok untuk berbagai kebutuhan, mulai dari remote akses hingga keamanan jaringan.\n\n🔹 Domain & Hosting\nMulai bisnis online Anda dengan domain yang keren dan hosting yang cepat serta aman. Support terbaik untuk situs Anda aktif 24/7!\n\n🔹 VPS (Virtual Private Server)\nDapatkan VPS dengan berbagai pilihan server datacenter lokal dan internasional yang andal serta uptime tinggi. Cocok untuk website, aplikasi, dan kebutuhan server lainnya.\n\n🔹 IP Publik\nButuh IP Publik untuk bisnis atau proyek Anda? Kami menyediakan IP Publik dengan berbagai paket menarik!\n\n🔹 IT Consultant\nBingung dengan kebutuhan IT bisnis Anda? Konsultasikan dengan kami, tim ahli siap memberikan solusi terbaik!\n\nJangan lewatkan kesempatan untuk meningkatkan performa bisnis Anda dengan layanan kami. Hubungi kami sekarang untuk penawaran spesial bulan ini! 💸✨\n\n💬 Kontak Kami Sekarang\nKlik untuk chat: 085240346020\n\n🌐 Kunjungi website kami: https://digicore.web.id\n\nSalam hangat,\nTim Digicore";
  
  if($data['sender'] != 'status' AND $data['category'] == 'incoming_message'){
    $tujuan = $data['sender'];
    $server = $data['route'];
    // $pesan = "Maaf lagi sibuk";
    sleep(10);
    $response = kirim_pesan($tujuan,$pesan,$server);
  }
  
  if($data['category'] == 'status' AND $data['status'] = 'disconnected'){
    $pesan = $data['route']. ' ' . $data['status'];
    if($data['route'] == 'server1'){
      $server = 'server2';
    }else{
      $server = 'server1';
      exit;
    }
    
    $data = ["tujuan" => $owner, "pesan" => $pesan];
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.digicore.web.id/send-message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_HTTPHEADER => array(
        'Apikey: 1d0bfc76eebe36f1e85e0871b584719c',
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    echo $response;
    
  }
  
  ?>