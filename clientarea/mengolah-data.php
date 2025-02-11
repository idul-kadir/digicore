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

        case 'Hosting':
          $berlaku = $data['masa_berlaku'];
          $kadaluarsa = date('Y-m-d', strtotime("+$berlaku days"));
          do{
            $cek_id = query("SELECT * FROM `l_hosting` WHERE id = '$id_layanan' ");
            $jml_baris = mysqli_num_rows($cek_id);
            if($jml_baris > 0){
              $id_layanan++;
            }
          }while($jml_baris > 0);
          $tambah_layanan = query("INSERT INTO `l_hosting`(`id`, `kode_produk`, `id_user`, `tgl_expired`, `perpanjang`, `status`) VALUES ('$id_layanan','$kode','$id','$kadaluarsa','$perpanjang','aktif')");
          if($tambah_layanan){
            $saldo = saldo($id,'kurang',$data['harga']);
            echo 'success|Layanan hosting berhasil dibuat';
          }
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

//menambahkan tiket baru
if(isset($_POST['new-tiket'])){
  $id_tiket = bersihkan($_POST['new-tiket']);
  $judul = bersihkan($_POST['judul']);
  $kategori = bersihkan($_POST['kategori']);
  $prioritas = bersihkan($_POST['prioritas']);
  $deskripsi = bersihkan($_POST['deskripsi']);
  $file = '';
  $alert_file = '';

  do{
    $id_baru = $id_tiket++;
    $cek = query("SELECT * FROM `tiket` WHERE id = '$id_baru' ");
  }while(mysqli_num_rows($cek)>0);

  if(isset($_FILES['lampiran']['name'])){
    $data_file = $_FILES['lampiran'];
    $list_format = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'tiff', 'ico', 'heif', 'heic', 'jxr', 'avif', 'webm', 'apng','pdf'];
    $nama_file = $data_file['name'];
    $ukuran = $data_file['size'];
    $cari_format = explode('.',$nama_file);
    if($ukuran < 5000000){
      if(in_array(strtolower(end($cari_format)), $list_format)){
        $file = 'assets/ticket/'.$id_baru.'.'.end($cari_format);
        move_uploaded_file($data_file['tmp_name'],$file);
      }else{
        $alert_file = ' file tidak dicantumkan karena format file tidak sesuai yang ditetapkan';
      }
    }else{
      $alert_file = ' file tidak dicantumkan karena melebihi ukuran yang ditetapkan';
    }
  }

  $tambah = query("INSERT INTO `tiket`(`id`, `judul`, `kategori`, `prioritas`, `deskripsi`, `lampiran`, `status`,`id_user`) VALUES ('$id_baru','$judul','$kategori','$prioritas','$deskripsi','$file','open','$id')");
  if($tambah){
    $nama_pengguna = pengguna($id)['nama'];
    $nomor = pengguna($id)['wa'];
    echo "success|Tiket berhasil dibuat. Saat ini sudah diteruskan ke Tim Support untuk secepatnya ditanggapi".$alert_file;
    $pesan = "*KONSULTASI*\n".
             "============================\n\n".
             "Nama : $nama_pengguna\n".
             "Nomor Wa : $nomor\n\n".
             "*Judul* : \n".
             "$judul".
             "\n\n".
             "*Deskripsi* :\n".
             "$deskripsi";
    pesan_administrator($pesan);
  }else{
    echo "error|Tiket GAGAL dibuat. Periksa koneksi jaringan anda dan coba lagi setelah 10 menit".$alert_file;
  }

}

if(isset($_POST['reply-ticket'])){
  $id_ticket = bersihkan($_POST['id-ticket']);
  $pesan = bersihkan($_POST['deskripsi']);
  $cek_ticket = query("SELECT * FROM `tiket` WHERE id = '$id_ticket' AND id_user = '$id' ");
  if(mysqli_num_rows($cek_ticket)>0){
    $id_percakapan = time();
    do{
      $id_percakapan++;
      $cek_id = query("SELECT * FROM `percakapan` WHERE id = '$id_percakapan' ");
    }while(mysqli_num_rows($cek_id)>0);
    $input_pesan = query("INSERT INTO `percakapan`(`id`, `id_topik`, `id_user`, `teks`) VALUES ('$id_percakapan','$id_ticket','$id','$pesan')");
    if($input_pesan){
      echo 'success|Pesan anda sudah diterima. Mohon untuk tidak melakukan pesan berulang yah';
    }else{
      echo 'error|Sepertinya terjadi kesalahan ketika anda mengirimkan pesan. Silahkan diulang yah kak';
    }
  }else{
    echo 'error|Opps, sepertinya form anda sudah dimodifikasi sebelumnya. Jika anda tidak pernah memodifikasi form, silahkan logout dan login kembali';
  }
}

if(isset($_POST['buat-website'])){
  $id_layanan = bersihkan($_POST['buat-website']);
  $domain = bersihkan($_POST['domain']);
  $cek_domain = query("SELECT * FROM `l_hosting` WHERE id = '$id_layanan' AND id_user = '$id' ");
  if(mysqli_num_rows($cek_domain)<1){
    $data = mysqli_fetch_assoc($cek_domain);
    if($data['domain'] != $domain){
      
    }else{
      echo 'error|Domain yang anda daftarkan sudah ada dalam server kami';
    }
  }else{
    echo 'error|Layanan anda tidak terdaftar';
  }
}

function buat_website($data){
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.digicore.web.id/cyberpanel/buat-website',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
      "domainName": "hosting.digicore.web.id",
      "ownerEmail": "info@cyberpanel.net",
      "packageName": "admin_basic",
      "websiteOwner": "srigit",
      "ownerPassword": "tes12345",
      "selectedACL": "user"
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    return $response;
}