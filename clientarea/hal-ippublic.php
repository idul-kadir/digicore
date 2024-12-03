<?php
session_start();
if(!isset($_SESSION['id'])){
  exit;
}
$id = $_SESSION['id'];
$_SESSION['halaman'] = 'ip-public';
require 'function.php';
?>
<style>
  h5{
    font-family: arial, calibri, sans-serif;
  }
  ul > li{
    line-height:35px;
  }
  table tr td{
    vertical-align: text-top
  }
</style>

<div class="row">
  <div class="col-sm-12 col-md-6"></div>
  <div class="col-sm-12 col-md-6">
    <div class="row">

    <?php
        $result = query("SELECT * FROM `produk` WHERE kategori = 'IP Public' ORDER BY harga ASC ");
        while($data = mysqli_fetch_assoc($result)){
      ?>
      <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
          <div class="card-body text-center pt-4">
            <i class="fa-brands fa-whatsapp fa-3x text-center"></i>
            <h5 class="card-title text-center" style="padding-bottom:5px"><?= $data['nama'] ?></h5>
            <h5><span class="badge bg-success"><?= rupiah($data['harga']); ?></span></h5>
            <hr style="margin-top:5px; margin-bottom:5px">
            <ul style="list-style-type: none; padding-left: 0; margin-top:20px">
              <?php
              $data_sk = json_decode($data['sk_terima'], true);
              foreach($data_sk as $sk){
                ?>
                <li><i class="fa-solid fa-check" style="color: #0fd23f;"></i> <?= $sk ?></li>
                <?php
              }
              $data_sk = json_decode($data['sk_tolak'], true);
              foreach($data_sk as $sk){
                if($sk != ''){
                  ?>
                  <li><i class="fa-solid fa-xmark" style="color: #df2046;"></i> <?= $sk ?></li>
                  <?php
                }
              }
              ?>
            </ul>
            <?php
            if(pengguna($id)['saldo'] >= $data['harga']){
              $status_tbl = '';
              $class = 'checkout';
            }else{
              $status_tbl = 'disabled';
              $class = '';
            }
            ?>
            <button type="button" class="btn btn-primary <?= $class ?>" kode-produk="<?= $data['kode'] ?>" <?= $status_tbl ?>><i class="fa-solid fa-cart-shopping"></i> Pesan</button>
          </div>
        </div>
      </div>
      <?php } ?>

    </div>
  </div>
</div>