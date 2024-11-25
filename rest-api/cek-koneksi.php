<?php
//halaman ini akan direfresh setiap 5 menit untuk mengecek status dari server backup
echo 'oke';
$data = json_decode(list_server(), true);

foreach($data['data'] as $server){
  $result = json_decode(cek_koneksi($server), true);
  if(isset($result['message'])){
    $hasil = ["category" => "status", "route" => 'backup_'.$server, "timestamp" => time(), "status" => "connected"];
  }else{
    $hasil = ["category" => "status", "route" => 'backup_'.$server, "timestamp" => time(), "status" => "DISCONNECT"];
  }
 echo kirim_respon(json_encode($hasil));
}

function list_server(){
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://backup.digicore.web.id/sessions?key=mysupersecretkey',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return $response;

}

function cek_koneksi($server){
  
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://backup.digicore.web.id/start-session?session=$server&scan=true",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  return $response;
  
}

function kirim_respon($respon){
  
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.digicore.web.id/webhook',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $respon,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
return $response;
  
}