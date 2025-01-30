<?php
$list_ip = [''];

$url = $_SERVER['REQUEST_URI'];
$data = json_decode(file_get_contents("php://input"));

if(isset($data)){
  $pecah = explode('/',$url);
  $keterangan = end($pecah);

  switch ($keterangan){
    case 'buat-website':
      $result = ["adminUser" => $_SERVER['USER_CP'], "adminPass" => $_SERVER['PASS_CP'], "domainName" => $data['domainName'], "ownerEmail" => $data['ownerEmail'], "packageName" => $data['packageName'], "websiteOwner" => $data['websiteOwner'], "ownerPassword" => $data['ownerPassword']];
    break;
    
    default: $result = ["status" => "Aksi tidak terdefinisi"];
  }

  echo json_encode(["result" => "okeh"]);
}