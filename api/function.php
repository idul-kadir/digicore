<?php

date_default_timezone_set('Asia/Makassar');

$nomor_server = ['6285240346020','6287840189270','66968385202'];
$list_server = glob('status-server/*.json');
$link_server = array(['nama' => 'server3', 'link' => 'serverwabackup.digicore.web.id'],['nama' => 'server1', 'link' => 'serverwa1.digicore.web.id'],['nama' => 'server2', 'link' => 'serverwa2.digicore.web.id']);

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

function kirim_pesan($pesan, $tujuan, $id_pesan = null, $r_server=null) {
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

    if($r_server != null){
      $server = $r_server;
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
  //global untuk link server
  global $link_server;
  for($i=0; $i<count($link_server); $i++){
    $nama = $link_server[$i]['nama'];
    $link = $link_server[$i]['link'];
    
    //mengecek status koneksi whatsapp di semua server
    $server = json_decode(detail_koneksi($link), true);
    if(isset($server['code'])){
      if($server['results'] != ''){
        $nomor = explode(':', $server['results'][0]['device'])[0];
        $data = ["code" => 200, "categori" => $nama, "status" => "connected", "number" => $nomor, "last_update" => time(), "link_server" => "https://$link"];
  
        $file = fopen("status-server/$nama.json","w+");
        fwrite($file, json_encode($data));
        fclose($file);
      }else{
        $data = ["code" => 501, "categori" => $nama, "status" => "disconnected", "number" => "-", "last_update" => time(), "link_server" => "https://$link"];
  
        $file = fopen("status-server/$nama.json","w+");
        fwrite($file, json_encode($data));
        fclose($file);
      }
    }else{
      $data = ["code" => 404, "categori" => $nama, "status" => "server down", "last_update" => time(), "link_server" => "https://$link"];
  
      $file = fopen("status-server/$nama.json","w+");
      fwrite($file, json_encode($data));
      fclose($file);
    }

  }
}

function detail_koneksi($link){
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://$link/app/devices",
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
      if(($wkt_sekarang - $data['id']) < 900){
        return 'false';
      }else{
        return 'true';
      }
    }else{
      return 'true';
    }
  }
}

function antrian($pesan,$tujuan,$id_layanan, $status = null, $server=null){
  $tujuan = format_nomor(bersihkan($tujuan));
  $i = 0;
  do{
    $id_pesan = time() + $i;
    $cek_id = query("SELECT * FROM `pesan` WHERE id = '$id_pesan' ");
    $i++;
  }while(mysqli_num_rows($cek_id) > 0);
  
  $pesan = bersihkan($pesan);
  if($status == null){
    if($server != null){
      query("INSERT INTO `pesan`(`id`, `tujuan`, `pesan`, `id_produk`, `status`,`keterangan`) VALUES ('$id_pesan','$tujuan','$pesan','$id_layanan','pending','$server')");  
    }else{
      query("INSERT INTO `pesan`(`id`, `tujuan`, `pesan`, `id_produk`, `status`) VALUES ('$id_pesan','$tujuan','$pesan','$id_layanan','pending')");
    }
  }else{
    if($server != null){
      query("INSERT INTO `pesan`(`id`, `tujuan`, `pesan`, `id_produk`, `status`,`keterangan`) VALUES ('$id_pesan','$tujuan','$pesan','$id_layanan','spam','$server')");  
    }else{
      query("INSERT INTO `pesan`(`id`, `tujuan`, `pesan`, `id_produk`, `status`) VALUES ('$id_pesan','$tujuan','$pesan','$id_layanan','spam')");
    }
  }
}

function detail_user($id){
  $cari_user = query("SELECT * FROM `user` WHERE id = '$id' ");
  if(mysqli_num_rows($cari_user)>0){
    $data = mysqli_fetch_assoc($cari_user);
    $result = ['nama' => $data['nama'], "wa" => $data['wa'], "saldo" => $data['saldo']];
  }else{
    $result = false;
  }
  return $result;
}

function produk($kode){
  $kode = bersihkan($kode);
  $cari = query("SELECT * FROM `produk` WHERE kode = '$kode' ");
  if(mysqli_num_rows($cari)>0){
    $data = mysqli_fetch_assoc($cari);
    $result = ["nama" => $data['nama'], "kategori" => $data['kategori'], "harga" => $data['harga'], "status" => $data['status']];
  }else{
    $result = ["nama" =>"-", "kategori" => "-", "harga" => "-", "status" => "-"];
  }
  return $result;
}

function rupiah($angka) {
  if($angka > 0){
    return "Rp " . number_format($angka, 0, ',', '.');
  }else{
    return "Rp 0";
  }
}

function tgl_indo($tanggal)
{
  if ($tanggal != '0000-00-00') {
    $bulan = array(
      1 =>   'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
  } else {
    return '-';
  }
}