<?php
include('assets/vendor/api-mikrotik/routeros_api.class.php');
date_default_timezone_set('Asia/Makassar');
$layanan = ['l_whatsapp','l_vpn'];

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
        'Apikey: v1IsN2FNL5WLALqGfWpOUAhthQR4Jch31737286333605',
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

function rupiah($angka) {
  if($angka > 0){
    return "Rp " . number_format($angka, 0, ',', '.');
  }else{
    return "Rp 0";
  }
}

function pengguna($id){
  $id = bersihkan($id);
  $cari = query("SELECT * FROM `user` WHERE id = '$id' ");
  if(mysqli_num_rows($cari)>0){
    $data = mysqli_fetch_assoc($cari);
    $result = ["nama" => $data['nama'], "wa" => $data['wa'], "saldo" => $data['saldo'], "email" => $data['email']];
  }else{
    $result = ["nama" => "-", "wa" => "-", "saldo" => 0,"email" => $data['email']];
  }
  return $result;
}

function buat_apikey($length = 32) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';

    // Membuat string acak
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    // Menambahkan microtime untuk keunikan ekstra
    $microTime = round(microtime(true) * 1000); // Waktu dalam milidetik
    return $randomString . $microTime;
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

function saldo($id_client,$keterangan,$jumlah){
  $cek_client = query("SELECT * FROM `user` WHERE id = '$id_client' ");
  if(mysqli_num_rows($cek_client)>0){
    if($keterangan == 'tambah'){
      $pengguna = pengguna($id_client);
      $saldo_akhir = $pengguna['saldo'] + $jumlah;
      $result = query("UPDATE `user` SET `saldo`='$saldo_akhir' WHERE id = '$id_client' ");
      if($result){
        return true;
      }else{
        return false;
      }
    }else{
      $pengguna = pengguna($id_client);
      $saldo_akhir = $pengguna['saldo'] - $jumlah;
      $result = query("UPDATE `user` SET `saldo`='$saldo_akhir' WHERE id = '$id_client' ");
      if($result){
        return true;
      }else{
        return false;
      }
    }
  }else{
    return false;
  }
}

function tambah_user_vpn($jenis){
  $waktu = time();
  
}

function get_konektor($jenis){
  $result = '';
  $cari = query("SELECT * FROM `connector` WHERE jenis = '$jenis' AND `status` = 'tidak aktif' ORDER BY id ASC LIMIT 1 ");
  if(mysqli_num_rows($cari)>0){
    $data = mysqli_fetch_assoc($cari);
    $result = $data['id'];
  }
  return $result;
}

function status_konektor($id,$status){
  $API = new RouterosAPI();
  query("UPDATE `connector` SET `status`='$status' WHERE id='$id' ");
  $cek = query("SELECT * FROM `connector` WHERE id='$id' ");
  $data = mysqli_fetch_assoc($cek);
  if($status == 'aktif'){
    if($data['jenis'] != 'Wireguard'){
      buat_akun($data['catatan'],$data['catatan'],strtolower($data['jenis']),$data['ip']);
    }
  }
}

function detail_connector($id){
  $result = query("SELECT * FROM `connector` WHERE id = '$id' ");
  return mysqli_fetch_assoc($result);
}

function buat_akun($nama,$pass,$servis,$ip){
  // Membuat objek API
  $API = new RouterosAPI();

  // Tentukan batas maksimum percobaan (misalnya, 5 kali percobaan)
  $max_retries = 5;
  $attempt = 0;
  $connected = false;

  while (!$connected && $attempt < $max_retries) {
    // Menghubungkan ke MikroTik
    if ($API->connect($_SERVER['IP_CHR'], $_SERVER['USER_CHR'], $_SERVER['PASS_CHR'])) {
      $connected = true;  // Jika berhasil, set connected ke true
      try {
        // Menjalankan perintah untuk menambahkan PPP secret
        $API->comm('/ppp/secret/add', [
          'name' => $nama,
          'password' => $pass,
          'service' => $servis,
          'profile' => 'VPN-TUNNEL',
          'comment' => '6 Desember 2024 pukul '.date('H:i:s'),
          'remote-address' => $ip
        ]);
      } catch (Exception $e) {
        // Tangani error dengan mencatat atau memberi pesan kesalahan
        $result = "Error: " . $e->getMessage();
      }
      // Disconnect setelah perintah dijalankan
      $API->disconnect();
    } else {
      $attempt++;  // Increment percobaan
      echo "Gagal menghubungkan ke MikroTik. Percobaan ke-$attempt...\n";
      if ($attempt < $max_retries) {
        // Tunggu 10 detik sebelum mencoba lagi
        sleep(10);
      }
    }
  }

  if (!$connected) {
    echo "Gagal menghubungkan setelah $max_retries percobaan.\n";
  }
    
}
function firewall($jenis,$ip,$src_port,$dst_port) {
  $api = new RouterosAPI();
  $natParams = [
    'chain' => 'dstnat',               // Chain
    'dst-address' => ip_public()['ip'],        // IP tujuan untuk NAT
    'protocol' => 'tcp',               // Protokol
    'dst-port' => $src_port,                // Port tujuan
    'action' => 'dst-nat',             // Tindakan
    'to-addresses' => $ip, // IP lokal
    'to-ports' => $dst_port,              // Port lokal
    'comment' => tgl_indo(date('Y-m-d')) .' - '. date('H:i:s'), // Catatan
];
  
  if ($api->connect($_SERVER['IP_CHR'], $_SERVER['USER_CHR'], $_SERVER['PASS_CHR'])) {
    if($jenis == 'tambah'){
      // Tambahkan aturan dst-nat menggunakan metode comm
      $api->comm('/ip/firewall/nat/add', $natParams);
    }elseif($jenis == 'hapus'){
      $list = $api->comm('/ip/firewall/nat/print');
      foreach($list as $data){
        if($data['chain'] == 'dstnat' AND $data['dst-port'] == $src_port){
          $id = $data['.id'];
          $api->comm('/ip/firewall/nat/remove', ['.id' => $id]);
        }
      }
    }

    $api->disconnect();
    
    return "Dst-NAT rule added successfully!";
  } else {
    return "Failed to connect to Mikrotik API.";
  }
}

function ip_public(){
  return ['dns' => 'vpn.digicore.web.id', 'ip' =>'146.19.216.27'];
}

function hapus_firewall($dst_port){
  $api = new RouterosAPI();
  if ($api->connect($_SERVER['IP_CHR'], $_SERVER['USER_CHR'], $_SERVER['PASS_CHR'])) {
    // Tambahkan aturan dst-nat menggunakan metode comm
    $list = $api->comm('/ip/firewall/nat/print');
    foreach($list as $data){
      if($data['dst-port'] == $dst_port AND $data['chain'] == 'dstnat'){
        $id = $data['.id'];
        $api->comm('/ip/firewall/nat/remove', ['.id' => $id]);
      }
    }
    $api->disconnect();
    
    return "Dst-NAT rule added successfully!";
  } else {
    return "Failed to connect to Mikrotik API.";
  }
}

function qr($configFile){
  // Periksa apakah file ada
  if (!file_exists($configFile)) {
    die("File konfigurasi tidak ditemukan.");
  }
  
  // Baca isi file konfigurasi
  $configContent = file_get_contents($configFile);
  
  // URL API QR Code
  $apiUrl = "https://api.qrserver.com/v1/create-qr-code/";
  
  // Query parameter
  $queryParams = http_build_query([
    'size' => '150x150', // Ukuran QR Code
    'data' => $configContent, // Isi konfigurasi WireGuard
  ]);
  
  // URL lengkap
  $qrCodeUrl = $apiUrl . '?' . $queryParams;

  return $qrCodeUrl;
}

function antrian($pesan,$tujuan,$id_layanan){
  $tujuan = format_nomor(bersihkan($tujuan));
  $id_pesan = time();
  $pesan = bersihkan($pesan);
  query("INSERT INTO `pesan`(`id`, `tujuan`, `pesan`, `id_produk`, `status`) VALUES ('$id_pesan','$tujuan','$pesan','$id_layanan','pending')");
}

function cek_spam($pesan,$tujuan){
  $tujuan = format_nomor(bersihkan($tujuan));
  $pesan = bersihkan($pesan);
  $cek_data = query("SELECT * FROM `pesan` WHERE tujuan = '$tujuan' ORDER BY id DESC ");
  if(mysqli_num_rows($cek_data) < 1){
    return 'true';
  }else{
    $data = mysqli_fetch_assoc($cek_data);
    if(substr($data['pesan'],0,99) == substr($pesan,0,99)){
      $wkt_sekarang = time();
      if(($wkt_sekarang - $data['id']) < 600){
        return 'false';
      }else{
        return 'true';
      }
    }else{
      return 'true';
    }
  }
}

function cek_layanan($id_user,$status){
  global $layanan;
  $jml = 0;
  foreach($layanan as $data){
    $cek_wa = query("SELECT * FROM $data WHERE id_user = '$id_user' AND `status` = '$status' ");
    $jml = $jml + mysqli_num_rows($cek_wa);
  }
  return $jml;
}

function pesan_administrator($pesan){
  kirim_pesan($pesan,'089669106718');
}

function percakapan($id_user,$id_topik,$teks){
  $id_percakapan = time();
  do{
    $id = $id_percakapan++;
    $cekId = query("SELECT * FROM `percakapan` WHERE id = '$id' ");
  }while(mysqli_num_rows($cekId)>0);
  query("INSERT INTO `percakapan`(`id`, `id_topik`, `id_user`, `teks`) VALUES ('$id','$id_topik','$id_user','$teks')");
}