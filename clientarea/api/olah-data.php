<?php
error_reporting(1);
require '../function.php';

header("Content-Type: application/json");

$keterangan = end(explode('/',$_SERVER['REDIRECT_URL']));
$jenis_request = $_SERVER['REQUEST_METHOD'];
$header = getallheaders();
$data = json_decode(file_get_contents("php://input"), true);

switch($keterangan){
  case 'send-message':
    if($jenis_request == 'POST'){
      if(isset($header['Apikey'])){
        $apikey = bersihkan($header['Apikey']);
        $cek_api = query("SELECT * FROM `l_whatsapp` WHERE apikey='$apikey' AND `status`='aktif' ");
        if(mysqli_num_rows($cek_api)>0){
          if(isset($data['pesan']) && isset($data['tujuan'])){
            $data_api = mysqli_fetch_assoc($cek_api);
            if($data_api['kode_produk'] == 'FREE'){
  
            }
          }else{
            $result = ['kode' => 501, "keterangan" => "Parameter required tidak dikenali"];
          }
        }else{
          $result = ['kode' => 501, "keterangan" => "Apikey tidak valid atau telah kadaluarsa"];
        }
      }else{
        $result = ['kode' => 501, "keterangan" => "Apikey tidak boleh kosong"];
      }
    }else{
      $result = ['kode' => 501, "keterangan" => "Request harus POST"];
    }
    break;
  
  default: $result = ['kode' => 404, "keterangan" => "Endpoint tidak diketahui"];
}

echo json_encode($result);