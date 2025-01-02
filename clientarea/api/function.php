<?php

date_default_timezone_set('Asia/Makassar');

$nomor_server = ['6285240346020','6287840189270'];

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

function kirim_pesan($pesan, $tujuan) {
    // Format nomor tujuan
    $tujuan = format_nomor($tujuan);

    // Siapkan data JSON
    $data = array(
        "tujuan" => $tujuan,
        "pesan" => $pesan
    );

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
    return $response;
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
    if(count($server1['results']) > 0){
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

  //mengecek status koneksi whatsapp di server 1
  $server2 = json_decode(cek_koneksi_wa2(), true);
  if(isset($server2['code'])){
    if(count($server2['results']) > 0){
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
  $data = json_decode(file_get_contents('status-server/'.$nama_file), true);
  $status = $data['status'];
  $update = $data['last_update'];
  if($status == 'connected'){
    return true;
  }else{
    return false;
  }
}

function generate_server_pengirim($no_tujuan){
  $cek = query("SELECT * FROM `pesan` WHERE tujuan = '$no_tujuan' AND `status` != 'pending' ORDER BY id DESC ");
  if(mysqli_num_rows($cek)>0){
    $data = mysqli_fetch_assoc($cek);
    $keterangan = $data['keterangan'];
    if($keterangan == 'server1.json'){
      $cek_status = baca_status($keterangan);
      if($cek_status == true){
        return 'server1.json';
      }else{
        $cek_status = baca_status('server2.json');
        if($cek_status == true){
          return 'server2.json';
        }else{
          return false;
        }
      }
    }else{
      $cek_status = baca_status($keterangan);
      if($cek_status == true){
        return 'server2.json';
      }else{
        $cek_status = baca_status('server1.json');
        if($cek_status == true){
          return 'server1.json';
        }else{
          return false;
        }
      }
    }
  }else{
    $list_data_server = ['server1.json','server2.json'];
    $rand = array_rand($list_data_server);
    $keterangan = $list_data_server[$rand];
    if($keterangan == 'server1.json'){
      $cek_status = baca_status($keterangan);
      if($cek_status == true){
        return 'server1.json';
      }else{
        $cek_status = baca_status('server2.json');
        if($cek_status == true){
          return 'server2.json';
        }else{
          return false;
        }
      }
    }else{
      $cek_status = baca_status($keterangan);
      if($cek_status == true){
        return 'server2.json';
      }else{
        $cek_status = baca_status('server1.json');
        if($cek_status == true){
          return 'server1.json';
        }else{
          return false;
        }
      }
    }
  }
}