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