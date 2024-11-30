<?php
session_start();
require 'function.php';

if(!isset($_SESSION['id'])){
  echo 'error|Sesi anda telah habis. silahkan logout dan login kembali';
  exit;
}

$id = $_SESSION['id'];

if(isset($_POST['checkout'])){
  $kode = bersihkan($_POST['kode-produk']);
  $tbl_perpanjang = bersihkan($_POST['perpanjang']);
  if($tbl_perpanjang == 'true'){
    $perpanjang = 'ya';
  }else{
    $perpanjang = 'tidak';
  }
  if(strpos(strtoupper($kode), 'FREE') !== false){
    $perpanjang = 'tidak';
    $cek = query("SELECT * FROM `l_whatsapp` WHERE id_user = '$id' AND kode_produk LIKE '%FREE%' ");
    if(mysqli_num_rows($cek)>0){
      echo 'error|Anda sudah memiliki paket FREE sebelumnya';
      exit;
    }
  }
  $cek_produk = query("SELECT * FROM `produk` WHERE kode = '$kode' ");
  if(mysqli_num_rows($cek_produk)>0){
    $data = mysqli_fetch_assoc($cek_produk);
    if(pengguna($id)['saldo'] >= $data['harga']){
      $id_layanan = time();
      $api_key = buat_apikey();
      $kadaluarsa = date('Y-m-d', strtotime("+1 month"));

      $saldo = saldo($id,'kurang',$data['harga']);
      if($saldo == true){
        $cek = query("INSERT INTO `l_whatsapp`(`id`, `kode_produk`, `id_user`, `apikey`, `tgl_expired`, `perpanjang`, `status`) VALUES ('$id_layanan','$kode','$id','$api_key','$kadaluarsa','$perpanjang','aktif')");
        if($cek){
          echo 'success|Layanan '.$data['nama'].' berhasil diaktifkan';
        }else{
          echo 'error|GAGAL memesan layanan';
        }
      }else{
        echo 'error|Sistem maintenance';
      }

    }else{
      echo 'error|Saldo anda tidak cukup';
    }
  }else{
    echo 'error|Produk tidak teridentifikasi';
    exit;
  }
}