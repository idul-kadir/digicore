<?php
session_start();
require '../function.php';

if(isset($_POST['daftar'])){
  $nama = bersihkan($_POST['nama']);
  $telp = bersihkan(format_nomor($_POST['telp']));
  $pass = bersihkan($_POST['password']);
  $acak = md5($_SERVER['PENGACAK']. md5($pass). $_SERVER['PENGACAK']);
  $cek_user = query("SELECT * FROM `user` WHERE wa = '$telp' ");
  if(mysqli_num_rows($cek_user)<1){
    $id = time();
    $result = query("INSERT INTO `user`(`id`, `nama`, `wa`, `password`) VALUES ('$id','$nama','$telp','$acak')");
    if($result){
      echo 'success|' . $nama . ' telah berhasil melakukan registrasi';
      $pesan = "*REGISTRASI BERHASIL*,\n==============================\n\nHallo _*$nama*_, proses pendaftaran anda di https://digicore.web.id telah berhasil dilakukan. Berikut kami kirimkan detail registrasi anda\n\n------------------------------------------------------\nNama Lengkap : $nama\nUsername : *$telp*\nPassword : $pass\nUrl : https://clientarea.digicore.web.id\n------------------------------------------------------\n\nTerima Kasih atas kepercayaan anda kepada kami\n\n*Salam DigiCore*";
      kirim_pesan($pesan,$telp);
      $_SESSION['id'] = $id;
    }
  }else{
    echo 'error|Nomor Telp sudah terdaftar. Silahkan gunakan fitur lupa password untuk melakukan reset password dan pastikan nomor telp anda terdaftar di Whatsapp';
  }
}

if(isset($_POST['login'])){
  $username = bersihkan($_POST['username']);
  $password = md5($_SERVER['PENGACAK'] . md5(bersihkan($_POST['password'])) . $_SERVER['PENGACAK']);
  $result = query("SELECT * FROM `user` WHERE `wa` = '$username' ");
  if(mysqli_num_rows($result)>0){
    $data = mysqli_fetch_assoc($result);
    $pass = $data['password'];
    if($pass == $password){
      echo 'success|Login berhasil';
      $_SESSION['id'] = $data['id'];
      if($username == $_SERVER['PENGELOLA']){
        $_SESSION['pengelola'] = $username;
      }
    }else{
      echo 'error|Login GAGAL. Password salah!!!';
    }
  }else{
    echo 'error|Username tidak terdaftar';
  }
}