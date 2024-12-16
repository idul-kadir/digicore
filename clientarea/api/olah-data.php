<?php
error_reporting(1);
require '../function.php';

header("Content-Type: application/json");

$keterangan = end(explode('/',$_SERVER['REDIRECT_URL']));
$jenis_request = $_SERVER['REQUEST_METHOD'];

switch($keterangan){
  case 'send-message':
    if($jenis_request == 'POST'){

    }else{
      $result = ['kode' => 500, "keterangan" => "Request harus POST"];
    }
    break;
  
  default: $result = ['kode' => 404, "keterangan" => "Tujuan tidak diketahui"];
}

echo json_encode($result);