<?php
error_reporting(1);
require 'fungsi.php';
date_default_timezone_set('Asia/Makassar');
$kategori = explode('/',$_SERVER['REQUEST_URI']);
$kategori = end($kategori);

header("Content-Type:application/json");

$metode_pengiriman = $_SERVER['REQUEST_METHOD'];

if($metode_pengiriman == 'POST'){
  switch($kategori){
    case 'registrasi':
      if(isset($_POST['nama']) AND isset($_POST['no-wa']) AND isset($_POST['domain'])){
        $nama = bersihkan($_POST['nama']);
        $no_wa = bersihkan($_POST['no-wa']);
        $domain = bersihkan($_POST['domain']);
        $ip = gethostbyname($domain);
        $apikey = md5($nama.time());
        $expired = date('Y-m-d', strtotime('+10 days'));
        $id = time();
        $cek = query("INSERT INTO `user`(`id`, `nama`, `wa`, `apikey`, `ip_address`, `expired`, `saldo`, `status`) VALUES ('$id','$nama','$no_wa','$apikey','$ip','$expired','0','aktif')");
        if($cek){
          $pesan = "Hallo, $nama, \nSelamat bergabung dengan _*Ikad - Developer WAG*_. Kami memberikan fitur gratis sampai dengan *". tgl_indo($expired) ."* sebagai percobaan layanan notifikasi whatsapp.\nTerima Kasih sudah menggunakan layanan kami";
          antrian($no_wa,$pesan,$id);
          $result = ["status" => 200, "deskripsi" => "berhasil melakukan registrasi"];
        }
      }else{
        $result = ["status" => 501, "deskripsi"=> "Parameter nama, no-wa, dan domain tidak bisa kosong"];
      }
      break;

    case 'send-message':
      $header = getallheaders();
      $data_json = json_decode(file_get_contents("php://input"), true);
      if(isset($header['Apikey'])){
        $apikey = bersihkan($header['Apikey']);
        $cek_api = query("SELECT * FROM `user` WHERE apikey = '$apikey' ");
        if(mysqli_num_rows($cek_api)>0){
          $data = mysqli_fetch_assoc($cek_api);
          $id_user = $data['id'];
          $sekarang = strtotime(date('Y-m-d'));
          $expired = strtotime($data['expired']);
          if($expired >= $sekarang){
            if(isset($data_json['pesan']) != '' AND isset($data_json['tujuan']) != ''){
              $pesan = bersihkan($data_json['pesan']);
              $tujuan = bersihkan($data_json['tujuan']);
              $tujuan = formatNomor($tujuan);
              $id_pesan = time();
              $hasil = json_decode(antrian($tujuan,$pesan,$id_pesan), true);
              if($hasil['status'] == 200){
                query("INSERT INTO `riwayat`(`id_user`, `id_message`) VALUES ('$id_user','$id_pesan')");
                $result = ["status" => 200, "deskripsi" => "Pesan sudah masuk ke antrian"];
              }else{
                $result = ["status" => $hasil['status'], "deskripsi" => $hasil['deskripsi']];
              }
            }else{
              $result = ["status" => 501, "deskripsi" => "Pesan dan/atau nomor tujuan kosong"];
            }
          }else{
            $result = ["status" => 501, "deskripsi" => "Apikey anda sudah expired"];
          }
        }else{
          $result = ["status" => 501, "deskripsi" => "Apikey tidak terdaftar"];
        }
      }else{
        $result = ["status" => 501, "deskripsi" => "Apikey tidak boleh kosong"];
      }
      break;

    case 'detail':
      $header = getallheaders();
      if(isset($header['Apikey'])){
        $apikey = bersihkan($header['Apikey']);
         $cek_api = query("SELECT * FROM `user` WHERE apikey = '$apikey' ");
        if(mysqli_num_rows($cek_api)>0){
          $data = mysqli_fetch_assoc($cek_api);
          $id_user = $data['id'];
          $sekarang = strtotime(date('Y-m-d'));
          $expired = strtotime($data['expired']);
          $riwayat_pesan = [];
          $list_pesan = query("SELECT riwayat.id_user, riwayat.id_message, message.* FROM `riwayat` JOIN `message` ON riwayat.id_message = message.id_pesan WHERE riwayat.id_user = '$id_user' ORDER BY riwayat.id DESC ");
          foreach($list_pesan as $pesan){
            $riwayat_pesan[] = [
                        "tujuan" => $pesan['tujuan'],
                        "pesan" => $pesan['pesan'],
                        "status" => ucwords($pesan['status']),
                        "keterangan" => $pesan['keterangan'],
                        "tanggal" => tgl_indo(date('Y-m-d', $pesan['waktu'])).' '.date('H:i:s', $pesan['waktu'])
                      ];
          }
          $result = [
                      "status" => 200, 
                      "deskripsi" => [
                                      "nama" => $data['nama'], 
                                      "pemilik" =>$data['wa'],
                                      "ip aplikasi" => $data['ip_address'],
                                      "saldo" => $data['saldo'],
                                      "expired" => tgl_indo($data['expired']),
                                      "riwayat" => $riwayat_pesan
                                      ]
                            ];
        }else{
          $result = ["status" => 501, "deskripsi" => "Apikey tidak terdaftar"];
        }
      }else{
        $result = ["status" => 501, "deskripsi" => "Apikey tidak boleh kosong"];
      }
      break;

      case 'top-up':
        $id_member = bersihkan($_POST['id_member']);
        $dana = bersihkan($_POST['dana']);
        $cek_member = query("SELECT * FROM `user` WHERE `id` = '$id_member' ");
        if(mysqli_num_rows($cek_member) > 0){
          $data = mysqli_fetch_assoc($cek_member);
          $harga = $data['harga'];
          $wa = $data['wa'];
          $expired = $data['expired'];
          $nama = $data['nama'];
          $tgl_sekarang = date('Y-m-d');
          if($dana < $harga){
            $result = ["status" => 501, "deskripsi" => "Dana tidak cukup. minimal Rp. $harga"];
          }else{
            $sisa_saldo = $dana - $harga;
            if($expired > $tgl_sekarang){
              $masa_berlaku = date('Y-m-d', strtotime($expired . " +1 month"));
            }else{
              $masa_berlaku = date('Y-m-d', strtotime($expired . " +1 month"));
            }
            $tambah = query("UPDATE `user` SET `expired`='$masa_berlaku',`saldo`='$sisa_saldo',`status`='aktif' WHERE id = '$id_member' ");
            if($tambah){
              $pesan = "*PERPANJANGAN NOTIFIKASI*\n=========================\n\n_*$nama*_ telah berhasil memperpanjang layanan notifikasi whatsapp sampai tanggal". tgl_indo($masa_berlaku) .".\n\nTerima kasih sudah menggunakan layanan kami";
              $waktu = time();
              query("INSERT INTO `riwayat`(`id_user`, `id_message`) VALUES ('$id_member','$waktu')");
              antrian($wa,$pesan,$waktu);
              $result = ["status" => 200, "deskripsi" => "Layanan aktif sampai dengan ". tgl_indo($masa_berlaku)];
            }
          }
        }else{
          $result = ["status" => 501, "deskripsi" => "Member $id_member tidak ditemukan"];
        }
        break;

      default : $result = ['status' => 404, 'deskripsi' => $kategori.' tidak terdefinisi'];
  }

}else{
  $result = ["status" => "500", "deskripsi" => "Gunakan request POST"];
}

echo json_encode($result);