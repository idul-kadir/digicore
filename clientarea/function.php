<?php
include('assets/vendor/api-mikrotik/routeros_api.class.php');
date_default_timezone_set('Asia/Makassar');
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
    $result = ["nama" => $data['nama'], "wa" => $data['wa'], "saldo" => $data['saldo']];
  }else{
    $result = ["nama" => "-", "wa" => "-", "saldo" => 0];
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
  query("UPDATE `connector` SET `status`='$status' WHERE id='$id' ");
  $cek = query("SELECT * FROM `connector` WHERE id='$id' ");
  $data = mysqli_fetch_assoc($cek);
  if($status == 'aktif'){
    if($data['jenis'] != 'Wireguard'){
      $API->comm('/ppp/secret/add', [
        'name' => $data['catatan'],   // Nama pengguna untuk secret
        'password' => $data['catatan'], // Kata sandi untuk secret
        'service' => strtolower($data['jenis']),        // Jenis layanan (pptp, l2tp, ovpn, dll.)
        'profile' => 'VPN-TUNNEL',      // Profil pengguna (opsional)
        'comment' => tgl_indo(date('Y-m-d')).' - '. date('H:i:s'),       // Komentar untuk akun
        'remote-address' => $data['ip']
      ]);
    }
  }
}
