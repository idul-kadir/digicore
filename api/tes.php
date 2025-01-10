<?php
// require 'function.php';
$pesan = "*PEMBERITAHUAN*\n=================\n\nKami mendeteksi ada beberapa pesan pemberitahuan terkait *token penilaian yang tidak terkirim* dengan status `spam` dari provider. Untuk hal itu kami minta maaf dan akan segera memperbaikinya.\n\nBagi bapak/ibu yang tidak mendapatkan notifikasi token ada 2 cara untuk masuk ke lembar penilaian\n\n1. Login ke aplikasi teknikindustri-ung.tech - Pengelolaan - Data Bimbingan - Lembar Penilaian\n2. Menanyakan langsung kode token kepada Ketua Sidang\n\nCatatan untuk Ketua Sidang : Lembar penilaian untuk Ketua sidang itu memuat informasi token untuk semua dosen penguji. \n\n~Tim Pengembang";
$tujuan = ['6289669106718','628124466947','6281355552003','6285221993397','6285233111168','6285240560399','6285242128624','6285289407770'];

foreach($tujuan as $data){
  kirim($pesan,$data);
}

function kirim($pesan,$tujuan){
  $data = ["tujuan" => $tujuan,"pesan" => $pesan];
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
      'Apikey: SjXeeugR6zDOWvZZu0qDN3unwd5pKZ191735994927975',
      'Content-Type: application/json'
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  echo $response;

}