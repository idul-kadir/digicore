<?php
error_reporting(1);
require '../function.php';

header("Content-Type: application/json");

$keterangan = end(explode('/',$_SERVER['REDIRECT_URL']));
$jenis_request = $_SERVER['REQUEST_METHOD'];
$header = getallheaders();
$data = json_decode(file_get_contents("php://input"), true);
$pesan_sponsor = "\n\nPesan ini dikirim dari layanan whatsapp digicore.web.id dengan fitur gratis. Yuk cobain";

switch($keterangan){
  case 'send-message':
    if($jenis_request == 'POST'){
      if(isset($header['Apikey'])){
        $apikey = bersihkan($header['Apikey']);
        $cek_api = query("SELECT * FROM `l_whatsapp` WHERE apikey='$apikey' AND `status`='aktif' ");
        if(mysqli_num_rows($cek_api)>0){
          if(isset($data['pesan']) && isset($data['tujuan'])){
            if(cek_spam($data['pesan'], $data['tujuan']) == 'true'){
              $data_api = mysqli_fetch_assoc($cek_api);
              $id_layanan = $data_api['id'];
              if($data_api['kode_produk'] == 'FREE'){
                $thn_skrang = date('Y');
                $bln_skrang = date('n');
                $cek_pesan = query("SELECT * FROM `pesan` WHERE id_produk = '$id_layanan' AND MONTH(FROM_UNIXTIME(`id`)) = '$bln_skrang' AND YEAR(FROM_UNIXTIME(`id`)) = '$thn_skrang' ");
                if(mysqli_num_rows($cek_pesan) <= 350){
                  $pesan = $data['pesan']. $pesan_sponsor;
                  antrian($pesan,$data['tujuan'],$id_layanan);
                  $jml_limit = 350 - mysqli_num_rows($cek_pesan) - 1;
                  $result = ['kode' => 200, "keterangan" => "Pesan sudah masuk antrian. Pesan gratis anda masih tersisa $jml_limit lagi"];    
                }else{
                  $result = ['kode' => 400, "keterangan" => "Apikey anda sudah mencapai limitnya"];
                }
              }elseif($data_api['kode_produk'] == 'OTP'){
                if(strlen($data['pesan']) > 100){
                  $pesan = substr($data['pesan'],0,99);
                }else{
                  $pesan = $data['pesan'];
                }
                antrian($pesan,$data['tujuan'],$id_layanan);
                $result = ['kode' => 200, "keterangan" => "Pesan OTP sudah masuk antrian."];
              }else{
                antrian($pesan,$data['tujuan'],$id_layanan);
                $result = ['kode' => 200, "keterangan" => "Pesan sudah masuk antrian."];
              }
            }else{
              $result = ['kode' => 501, "keterangan" => "Pesan terindikasi SPAM. Silahkan tunggu 10 menit baru pesan bisa dikirimkan kembali"];
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