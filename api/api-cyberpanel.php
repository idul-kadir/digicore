<?php
header("Content-Type: application/json");
$list_ip = [''];

$url = $_SERVER['REQUEST_URI'];
$data = json_decode(file_get_contents("php://input"), true);
$host = $_SERVER['HOST_CP'];

if(isset($data)){
  $pecah = explode('/',$url);
  $keterangan = end($pecah);

  switch ($keterangan){
    case 'buat-website':
      $kirim = ["adminUser" => $_SERVER['USER_CP'], "adminPass" => $_SERVER['PASS_CP'], "domainName" => $data['domainName'], "ownerEmail" => $data['ownerEmail'], "packageName" => $data['packageName'], "websiteOwner" => $data['websiteOwner'], "ownerPassword" => $data['ownerPassword'], "selectedACL" => "user"];
      $result = json_decode(buat_website($kirim), true);
    break;
    
    default: $result = ["status" => "Aksi tidak terdefinisi"];
  }

  echo json_encode($result);
}

function buat_website($data){
  global $host;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $host.'/api/createWebsite',
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

  // Menonaktifkan verifikasi SSL
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

  // Eksekusi request dan ambil responsenya
  $response = curl_exec($curl);

  // Pengecekan error eksekusi cURL
  if ($response === false) {
    echo "cURL Error: " . curl_error($curl); // Menampilkan error cURL jika request gagal
    curl_close($curl); // Menutup cURL jika error
    return false; // Mengembalikan false jika request gagal
  }

  // Pengecekan status HTTP response code
  $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Mendapatkan status code HTTP
  if ($http_code != 200) {
    echo "HTTP Error: " . $http_code . " - Response: " . $response; // Menampilkan error jika status code tidak 200 (OK)
    curl_close($curl); // Menutup cURL jika ada masalah HTTP
    return false; // Mengembalikan false jika status code bukan 200
  }

  // Tutup cURL setelah selesai
  curl_close($curl);

  return $response; // Mengembalikan response yang sukses
}
