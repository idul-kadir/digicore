<?php

date_default_timezone_set('Asia/Makassar');

$nomor_server = ['6285240346020','6287840189270'];
$list_server = glob('status-server/*.json');

$koneksi = mysqli_connect($_SERVER['HOST'], $_SERVER['USER_DB'], $_SERVER['PASS_DB'], $_SERVER['DB']);

function query($sql){
  global $koneksi;
  return mysqli_query($koneksi, $sql);
}

function bersihkan($data) {
  // Menghilangkan spasi ekstra dan karakter non-printable
  $data = trim($data);
  
  // Menghindari karakter-karakter spesial dengan htmlspecialchars
  $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
  
  // Menyaring karakter-karakter yang tidak diinginkan
  $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  
  // Jika Anda ingin menambahkan validasi khusus (misalnya validasi email atau URL)
  // Misalnya, jika inputnya adalah email:
  // $data = filter_var($data, FILTER_SANITIZE_EMAIL);
  
  return $data;
}

function kirim_pesan($pesan, $tujuan, $id_pesan = null) {
  $server = generate_server_pengirim($tujuan);
  if($server != false){  
    if($id_pesan == null){
      // Siapkan data JSON
      $data = array(
        "phone" => $tujuan,
        "message" => $pesan
      );
    }else{
      // Siapkan data JSON
      $data = array(
        "phone" => $tujuan,
        "message" => $pesan,
        "reply_message_id" => $id_pesan
      );
    }

    $data_server = json_decode(file_get_contents("status-server/$server"), true);
    $url = $data_server['link_server'].'/send/message';
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>json_encode($data),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $result = ["result" => json_decode($response, true), "server" => $server];
    return json_encode($result);
  }else{
    return false;
  }
}

function kirim_pesan_telegram($pesan){
  $pesan = str_replace(' ','%20', $pesan);
  file_get_contents("https://api.telegram.org/bot1168556933:AAETrMmLdgMaOhduC-4SBZe5RpkFl9HVOh0/sendMessage?chat_id=652919139&text=$pesan");
}

function format_nomor($nomor){
  $nomor = preg_replace('/[^0-9]/', '', $nomor);

    // Ganti "0" di awal nomor dengan "62" jika nomor dimulai dengan "0"
    if (strpos($nomor, '0') === 0) {
        $nomor = '62' . substr($nomor, 1);
    }

    return $nomor;

}

function cek_koneksi(){
  //mengecek status koneksi whatsapp di server 1
  $server1 = json_decode(cek_koneksi_wa1(), true);
  if(isset($server1['code'])){
    if($server1['results'] != ''){
      $nomor = explode(':', $server1['results'][0]['device'])[0];
      $data = ["code" => 200, "categori" => "server1", "status" => "connected", "number" => $nomor, "last_update" => time(), "link_server" => "https://serverwa1.digicore.web.id"];

      $file = fopen("status-server/server1.json","w+");
      fwrite($file, json_encode($data));
      fclose($file);
    }else{
      $data = ["code" => 501, "categori" => "server1", "status" => "disconnected", "number" => $nomor, "last_update" => time(), "link_server" => "https://serverwa1.digicore.web.id"];

      $file = fopen("status-server/server1.json","w+");
      fwrite($file, json_encode($data));
      fclose($file);
    }
  }else{
    $data = ["code" => 404, "categori" => "server1", "status" => "server down", "last_update" => time(), "link_server" => "https://serverwa1.digicore.web.id"];

    $file = fopen("status-server/server1.json","w+");
    fwrite($file, json_encode($data));
    fclose($file);
  }

  //mengecek status koneksi whatsapp di server 2
  $server2 = json_decode(cek_koneksi_wa2(), true);
  if(isset($server2['code'])){
    if($server2['results'] != ''){
      $nomor = explode(':', $server2['results'][0]['device'])[0];
      $data = ["code" => 200, "categori" => "server2", "status" => "connected", "number" => $nomor, "last_update" => time(), "link_server" => "https://serverwa2.digicore.web.id"];

      $file = fopen("status-server/server2.json","w+");
      fwrite($file, json_encode($data));
      fclose($file);
    }else{
      $data = ["code" => 501, "categori" => "server2", "status" => "disconnected", "number" => $nomor, "last_update" => time(), "link_server" => "https://serverwa2.digicore.web.id"];

      $file = fopen("status-server/server2.json","w+");
      fwrite($file, json_encode($data));
      fclose($file);
    }
  }else{
    $data = ["code" => 404, "categori" => "server2", "status" => "server down", "last_update" => time(), "link_server" => "https://serverwa2.digicore.web.id"];

    $file = fopen("status-server/server2.json","w+");
    fwrite($file, json_encode($data));
    fclose($file);
  }
}

function cek_koneksi_wa1(){
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://serverwa1.digicore.web.id/app/devices',
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

function cek_koneksi_wa2(){
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://serverwa2.digicore.web.id/app/devices',
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

function baca_status($nama_file){
  if(!file_exists('status-server/'.$nama_file)){
    cek_koneksi();
    sleep(2);
  }
  $format = explode('.',$nama_file);
  if(end($format) != 'json'){
    return false;
  }else{
    $data = json_decode(file_get_contents('status-server/'.$nama_file), true);
    $status = $data['status'];
    $update = $data['last_update'];
    if($status == 'connected'){
      return true;
    }else{
      return false;
    }
  }
}

function generate_server_pengirim($no_tujuan){
  global $list_server;
  $stats = false;
  $cek = query("SELECT * FROM `pesan` WHERE tujuan = '$no_tujuan' AND `status` != 'pending' ORDER BY id DESC ");
  if(mysqli_num_rows($cek)>0){
    $data = mysqli_fetch_assoc($cek);
    if(baca_status($data['keterangan']) == true){
      $stats = $data['keterangan'];
    }else{
      $loop = 0;
      do{
        $server = str_replace('status-server/','',$list_server[array_rand($list_server)]);
        $loop++;
      }while(baca_status($server) == false OR $loop < 5);
      if(baca_status($server) == true){
        $stats = $server;
      }
    }
  }else{
    $loop = 0;
    do{
      $server = str_replace('status-server/','',$list_server[array_rand($list_server)]);
      $loop++;
    }while(baca_status($server) == false OR $loop < 5);
    if(baca_status($server) == true){
      $stats = $server;
    }
  }
  return $stats;
}


function cek_spam($pesan,$tujuan){
  $tujuan = format_nomor(bersihkan($tujuan));
  $pesan = bersihkan($pesan);
  $cek_data = query("SELECT * FROM `pesan` WHERE tujuan = '$tujuan' ORDER BY id DESC ");
  if(mysqli_num_rows($cek_data) < 1){
    return 'true';
  }else{
    $data = mysqli_fetch_assoc($cek_data);
    if(substr($data['pesan'],0,250) == substr($pesan,0,99)){
      $wkt_sekarang = time();
      if(($wkt_sekarang - $data['id']) < 9000){
        return 'false';
      }else{
        return 'true';
      }
    }else{
      return 'true';
    }
  }
}

function antrian($pesan,$tujuan,$id_layanan, $status = null){
  $tujuan = format_nomor(bersihkan($tujuan));
  $i = 0;
  do{
    $id_pesan = time() + $i;
    $cek_id = query("SELECT * FROM `pesan` WHERE id = '$id_pesan' ");
    $i++;
  }while(mysqli_num_rows($cek_id) > 0);
  
  $pesan = bersihkan($pesan);
  if($status == null){
    query("INSERT INTO `pesan`(`id`, `tujuan`, `pesan`, `id_produk`, `status`) VALUES ('$id_pesan','$tujuan','$pesan','$id_layanan','pending')");
  }else{
    query("INSERT INTO `pesan`(`id`, `tujuan`, `pesan`, `id_produk`, `status`) VALUES ('$id_pesan','$tujuan','$pesan','$id_layanan','spam')");
  }
}