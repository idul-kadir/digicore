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