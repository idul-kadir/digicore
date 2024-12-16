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
      $kategori = $data['kategori'];
      switch($kategori){

        case 'Whatsapp':
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
        break;

        case 'VPN Tunnel':
          $konektor1 = get_konektor('Wireguard');
          if($kode != 'TUNNEL 80'){
            $konektor2 = get_konektor('ANY');
          }else{
             $konektor2 = get_konektor('OVPN');
          }
          $berlaku = $data['masa_berlaku'];
          $kadaluarsa = date('Y-m-d', strtotime("+$berlaku days"));
          $cek = query("INSERT INTO `l_vpn`(`id`, `kode_produk`, `id_user`, `konektor1`, `konektor2`, `tgl_expired`, `perpanjang`, `status`) VALUES ('$id_layanan','$kode','$id','$konektor1','$konektor2','$kadaluarsa','$perpanjang','aktif')");
          if($cek){
            $saldo = saldo($id,'kurang',$data['harga']);
            status_konektor($konektor1,'aktif');
            status_konektor($konektor2,'aktif');
            echo 'success|VPN Tunnel berhasil dicheckout';
            exit;
          }
          echo 'success|tunnel';
        break;

      }

    }else{
      echo 'error|Saldo anda tidak cukup';
    }
  }else{
    echo 'error|Produk tidak teridentifikasi';
    exit;
  }
}

if(isset($_POST['tambah-firewall'])){
  $id_vpn = bersihkan($_POST['tambah-firewall']);
  $ip_vpn = bersihkan($_POST['ip-vpn']);
  $dst_port = bersihkan($_POST['dst-port']);
  $id_tunnel = bersihkan($_POST['id-tunnel']);
 
  if($id_tunnel == ''){
    do{
      $s_port = rand(1000,9999);
      $cek = query("SELECT * FROM `tunnel` WHERE src_port = '$s_port' ");
    }while(mysqli_num_rows($cek)>0);
    $result = query("INSERT INTO `tunnel`(`id_vpn`, `ip`, `src_port`, `dst_port`) VALUES ('$id_vpn','$ip_vpn','$s_port','$dst_port')");
  }else{
    $cek = query("SELECT * FROM `tunnel` WHERE id = '$id_tunnel' ");
    if(mysqli_num_rows($cek)>0){
      $data = mysqli_fetch_assoc($cek);
      firewall('hapus','',$data['src_port'],'');
      $result = query("UPDATE `tunnel` SET `ip`='$ip_vpn',`dst_port`='$dst_port' WHERE id = '$id_tunnel' ");
      $s_port = $data['src_port'];    
    }
  }
  firewall('tambah',$ip_vpn,$s_port,$dst_port);
  if($result){
    echo 'success|Firewall VPN berhasil dibuat';
    exit;
  }else{
    echo 'error|Gagal di buatkan FireWall';
  }
}