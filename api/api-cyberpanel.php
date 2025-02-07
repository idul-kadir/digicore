<?php
header("Content-Type: application/json");
$list_ip = [''];

$url = $_SERVER['REQUEST_URI'];
$data = json_decode(file_get_contents("php://input"), true);
$host = $_SERVER['HOST_CP'];

if(isset($data)){
  $pecah = explode('/',$url);
  $keterangan = end($pecah);

  switch ($keterangan){
    case 'buat-website':
      $kirim = ["adminUser" => $_SERVER['USER_CP'], "adminPass" => $_SERVER['PASS_CP'], "domainName" => $data['domainName'], "ownerEmail" => $data['ownerEmail'], "packageName" => $data['packageName'], "websiteOwner" => $data['websiteOwner'], "ownerPassword" => $data['ownerPassword'], "selectedACL" => "user"];
      $result = json_decode(buat_website($kirim), true);
    break;
    
    default: $result = ["status" => "Aksi tidak terdefinisi"];
  }

  echo json_encode($result);
}

function buat_website($data){
  global $host;
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => $host.'/api/createWebsite',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  return $response;

}