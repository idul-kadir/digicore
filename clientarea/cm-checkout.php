<?php
session_start();
require 'function.php';
if(!isset($_SESSION['id'])){
  exit;
}

if(isset($_POST['checkout'])){
  $kategori = $_POST['checkout'];
  $kode_produk = bersihkan($_POST['kode-produk']);
  switch($kategori){
    case 'whatsapp':
      $cari = query("SELECT * FROM `produk` WHERE kode = '$kode_produk' ");
      if(mysqli_num_rows($cari)>0){
        $data = mysqli_fetch_assoc($cari);
        ?>
        <p>Anda akan memesan layanan <?= $data['kategori'] ?> dengan kode paket <b><?= $data['nama'] ?></b> dengan harga <span class="badge bg-success"><?= rupiah($data['harga']) ?></span>. Pastikan anda sudah membaca syarat dan ketentuan dari paket tersebut sebelum menyelesaikan proses checkout ini. <mark>Segala bentuk kesalahan ataupun kelalaian adalah tanggung jawab pengguna sepenuhnya.</mark><i class="fa-regular fa-face-smile-beam"></i></p>
        <p>Terima kasih atas kepercayaan anda pada digicore</p>
        <?php
        if($kode_produk == 'FREE'){
          $p_otomatis = 'disabled';
        }else{
          $p_otomatis = 'checked';
        }
        ?>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="perpanjang-otomatis" <?= $p_otomatis; ?>>
          <label class="form-check-label" for="perpanjang-otomatis">Perpanjang Otomatis</label>
        </div>
        <?php
      }else{
        echo 'Terjadi kesalahan sistem. Silahkan tutup browser anda dan login kembali';
        exit;
      }
      break;
  }
}