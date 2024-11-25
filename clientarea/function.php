<?php
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
