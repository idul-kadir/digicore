<?php
date_default_timezone_set('Asia/Makassar');
// require 'koneksi.php';
$owner = '089669106718';

function kirim_pesan($tujuan,$pesan,$server){
  $curl = curl_init();

  if($server == 'server1'){
    $url = 'https://wa1.digicore.web.id/api/send-message';
  }elseif($server == 'server2'){
    $url = 'https://wa2.digicore.web.id/api/send-message';
  }

  $data = json_encode(["to" => formatNomor($tujuan), "message" => $pesan]);

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);

  $result = json_decode($response, true);
  if(isset($result['success'])){
    $hasil = ["status" => 200, "message" => "Pesan berhasil terkirim"];
  }else{
    $hasil = ["status" => 500, "message" => "Pesan gagal terkirim"];
  }

  return json_encode($hasil);

}

function kirim_pesan_backup($tujuan,$pesan,$server){

  $data = ["session" => $server, "to" => formatNomor($tujuan), "text" => $pesan];

  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://backup.digicore.web.id/send-message',
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

    $result = json_decode($response, true);
    if(isset($result['data']['status'])){
      if($result['data']['status'] == 1){
        $hasil = ["status" => 200, "message" => "Pesan berhasil terkirim"];
      }else{
        $hasil = ["status" => 500, "message" => "Pesan gagal terkirim"];
      }
    }else{
      $hasil = ["status" => 500, "message" => "Pesan gagal terkirim"];
    }

    return json_encode($hasil);
}

function formatNomor($number) {
    // Hapus semua karakter selain angka
    $number = preg_replace('/\D/', '', $number);

    // Jika digit pertama adalah '0', ganti dengan '62'
    if (substr($number, 0, 1) === '0') {
        $number = '62' . substr($number, 1);
    }

    return $number;
}

function query($query){
  global $koneksi;
  return mysqli_query($koneksi,$query);
}

function bersihkan($string){
  global $koneksi;
  return mysqli_real_escape_string($koneksi,$string);
}

function antrian($tujuan,$pesan, $id_pesan){
  if(isset($_POST['session'])){
    $session = $_POST['session'];
  }else{
    $session = 'all';
  }

  $tujuan = formatNomor($tujuan);
  $waktu = time();

  $cek = query("SELECT * FROM `message` WHERE tujuan = '$tujuan' AND pesan = '$pesan' ORDER BY waktu DESC ");

  if(mysqli_num_rows($cek)<1){
    $tambah = query("INSERT INTO `message`(`tujuan`, `pesan`, `waktu`, `status`, `sesi`, `id_pesan`) VALUES ('$tujuan','$pesan','$waktu','pending','$session','$id_pesan')");
    if($tambah){
      $result = ["status" => 200, "deskripsi" => "pesan sudah masuk kedalam antrian"];
    }else{
      $result = ["status" => 501, "deskripsi" => "Gagal menambahkan ke antrian"];
    }
  }else{
    $data = mysqli_fetch_assoc($cek);
    if(($waktu - $data['waktu']) > 600){
      $tambah = query("INSERT INTO `message`(`tujuan`, `pesan`, `waktu`, `status`, `sesi`, `id_pesan`) VALUES ('$tujuan','$pesan','$waktu','pending','$session','$id_pesan')");
      if($tambah){
        $result = ["status" => 200, "deskripsi" => "pesan sudah masuk kedalam antrian"];
      }else{
        $result = ["status" => 501, "deskripsi" => "Gagal menambahkan ke antrian"];
      }
    }else{
      $result = ["status" => 501, "deskripsi" => "Pesan dan tujuan yang sama sudah ada dalam antrian."];
    }
  }
  return json_encode($result);
}

function pilihServer(){
  $cari_server = query("SELECT * FROM `message` WHERE `status` != 'pending' AND keterangan != '' ORDER BY id DESC LIMIT 1");
  foreach($cari_server as $server){
    if($server['sesi_pengirim'] == '' OR $server['sesi_pengirim'] == 'server1'){
      return 'server2';
    }else{
      return 'server1';
    }
  }
}

function cekNomor($nomor){
  $nomor = formatNomor($nomor);
  $jumlah = query("SELECT * FROM `message` WHERE tujuan = '$nomor' AND sesi_pengirim != '' ORDER BY id DESC ");
  if(mysqli_num_rows($jumlah)>0){
    $data = mysqli_fetch_assoc($jumlah);
    return $data['sesi_pengirim'];
  }else{
    return pilihServer();
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