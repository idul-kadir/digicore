<?php
header("Content-Type:application/json");
require 'fungsi.php';

$request = $_SERVER['REQUEST_METHOD'];

switch($request){
  case 'GET':
    $kategori = $_GET['kategori'];
    if($kategori == 'get-client'){
      $result = query("SELECT * FROM `user`");
      while($data=mysqli_fetch_assoc($result)){
        $results[] = ["id" => $data['id'],"nama" => $data['nama'], "wa owner" => $data['wa'], "apikey" => $data['apikey'], "kadaluarsa" => tgl_indo($data['expired']), "expired date" => $data['expired'], "harga" => $data['harga'], "status" => $data['status']];
      }
      $respon = ["status" => 200, "data" => $results];
    }
  break;

  case 'POST':
  break;

  case 'PUT':
  break;

  case 'DELETE':
  break;

  default : $respon = ["status" => 501, "message" => "Request method tidak terdefinisi"];
}

echo json_encode($respon);