<?php
session_start();
require '../function.php';

if(!isset($_SESSION['pengelola'])){
  echo 'error|Anda bukan pengelola';
  exit;
}

if(isset($_POST['tambah-produk'])){
  $kode = bersihkan($_POST['kode']);
  $nama = bersihkan($_POST['nama']);
  $kategori = bersihkan($_POST['kategori']);
  $harga = bersihkan($_POST['harga']);
  $s_k_terima = bersihkan($_POST['s-k-terima']);
  $s_k_tolak = bersihkan($_POST['s-k-tolak']);
  $cek = query("SELECT * FROM `produk` WHERE kode = '$kode' ");
  if(mysqli_num_rows($cek) < 1){
    $pecah1 = json_encode(explode(';',$s_k_terima));
    $pecah2 = json_encode(explode(';',$s_k_tolak));
    $result = query("INSERT INTO `produk`(`kode`, `nama`, `kategori`, `harga`, `sk_terima`, `sk_tolak`, `status`) VALUES ('$kode','$nama','$kategori','$harga','$pecah1','$pecah2','aktif')");
    if($result){
      echo 'success|Produk berhasil ditambahkan';
    }else{
      echo 'error|Gagal menambahkan produk. Terjadi kesalahan dalam query';
    }
  }else{
    echo 'error|Kode sudah ada dalam database';
  }
}

if(isset($_POST['tambah-saldo'])){
  $client = bersihkan($_POST['client']);
  $jumlah = bersihkan($_POST['saldo']);
  $pengguna = pengguna($client);
  $cek = query("SELECT * FROM `user` WHERE id = '$client' ");
  if(mysqli_num_rows($cek)>0){
    $top_up = $pengguna['saldo'] + $jumlah;
    $result = query("UPDATE `user` SET `saldo`='$top_up' WHERE id = '$client' ");
    if($result){
      echo 'success|Top up berhasil dilakukan';
    }else{
      echo 'error|Top up gagal dilakukan';
    }
  }else{
    echo 'error|User tidak terdaftar';
  }
}

if(isset($_POST['cek-ip'])){
  $tipe = $_POST['tipe-konektor'];
  $file = $_FILES['file'];
  $ip = 'IP tidak ditemukan';
  if($tipe == 'Wireguard'){
    $nama_file = $file['name'];
    $pecah = explode('.',$nama_file);
    if(end($pecah) != 'conf'){
      echo 'File konfigurasi harus berformat .conf';
    }else{
      $file_config = file($file['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach($file_config as $konfig){
        $text = $konfig;
        $word = "Address";

        if (strpos($text, $word) !== false) {
            $text = str_replace(' ','',$text);
            $text = strtolower($text);
            $pisah = explode('/',$text);
            $ip = str_replace('address=','',$pisah[0]);
        }
      }
    }
    echo $ip;
  }
}

if(isset($_POST['tambah-konektor'])){
  $jenis = bersihkan($_POST['jenis-konektor']);
  $ip = bersihkan($_POST['ip']);
  $file_konfig = '';
  $id = time();

  $cek_ip = query("SELECT * FROM `connector` WHERE ip = '$ip' ");
  if(mysqli_num_rows($cek_ip)<1){

    if(isset($_FILES['file-konfig'])){
      $file = $_FILES['file-konfig'];
      if($jenis == 'Wireguard'){
        $pecah = explode('.', $file['name']);
        if(strtolower(end($pecah)) == 'conf'){
          $file_nama = "DigiCore-Wireguard-$id.conf";
          move_uploaded_file($file['tmp_name'], '../assets/konektor/'.$file_nama);
          $file_konfig = 'assets/konektor/'.$file_nama;
        }
      }
    }
    $cek = query("INSERT INTO `connector`(`id`, `jenis`, `ip`, `config`, `status`) VALUES ('$id','$jenis','$ip','$file_konfig','tidak aktif')");
    if($cek){
      echo 'success|Konektor berhasil ditambahkan';
    }else{
      echo 'error|Konektor GAGAL ditambahkan';
    }
  }else{
    echo 'error|IP sudah ditemukan di database';
  }
}