<?php
session_start();
if(!isset($_SESSION['id'])){
  exit;
}
$id = $_SESSION['id'];
require 'function.php';
$_SESSION['halaman'] = 'whatsapp';
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

  <div class="col-sm-12 col-md-6 col-lg-6">
  <?php
  $cari_layanan = query("SELECT * FROM `l_whatsapp` WHERE id_user = '$id' ORDER BY id DESC ");
  if(mysqli_num_rows($cari_layanan)>0){
    while($data = mysqli_fetch_assoc($cari_layanan)){
      $produk = produk($data['kode_produk']);
      if($data['kode_produk'] == 'OTP'){
        $pesan_contoh = 'Kode OTP anda 91158';
      }else{
        $pesan_contoh = 'Pesan ini dikirim dari layanan DigiCore dengan kategori '.$produk['nama'];
      }
  ?>
    <div class="card">
      <h5 class="card-header text-white bg-primary"><?= $produk['nama'] ?></h5>
      <div class="card-body mt-2">
        <table>
          <tr>
            <td>Expired</td>
            <td style="width:25px; text-align:center">:</td>
            <td><?= tgl_indo($data['tgl_expired']) ?></td>
          </tr>
          <tr>
            <td>Perpanjang Otomatis</td>
            <td style="width:25px; text-align:center">:</td>
            <td>
              <?php
              if($data['perpanjang'] == 'tidak'){
                echo '<span class="badge bg-danger">Tidak</span>';
              }else{
                echo '<span class="badge bg-success">Ya</span>';
              }
              ?>
            </td>
          </tr>
        </table>
        <table class="mt-4">
          <tr>
            <td colspan="3">Dokumentasi API</td>
          </tr>
          <tr>
            <td>Request Method</td>
            <td style="width:25px; text-align:center">:</td>
            <td>POST</td>
          </tr>
          <tr>
            <td>Endpoint</td>
            <td style="width:25px; text-align:center">:</td>
            <td><?= $_SERVER['ENDPOINT'] ?></td>
          </tr>
        </table>
        <hr>
        <p>Dokumentasi Curl</p>
        <p>
          <code>
           <?php
            // Data dinamis
            $api = $data['apikey'];
            $tujuan = pengguna($id)['wa'];

            // Membuat string cURL yang valid
            $curl_script = <<<CURL
            curl --location 'https://api.digicore.web.id/send-message' \
            --header 'Apikey: {$api}' \
            --header 'Content-Type: application/json' \
            --data '{
                "tujuan": "{$tujuan}",
                "pesan": "{$pesan_contoh}"
            }'
            CURL;

            // Menampilkan hasil agar bisa dicopy ke Postman atau terminal
            echo "<pre>$curl_script</pre>";
            ?>
          </code>
        </p>
      </div>
    </div>
  <?php
    }
  }else{
    ?>
    <div class="alert alert-danger" role="alert">
      Opps!!! Anda tidak memiliki layanan whatsapp yang aktif. Anda bisa mencoba paket gratis untuk mempelajari dokumentasinya
    </div>
    <?php
  }
  ?>
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="row">

      <?php
        $result = query("SELECT * FROM `produk` WHERE kategori = 'whatsapp' ORDER BY harga ASC ");
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

<div class="modal fade" id="modal-checkout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel"><i class="fa-solid fa-cart-shopping"></i> Checkout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="content-checkout">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="proses-checkout"><i class="fa-solid fa-rupiah-sign fa-shake"></i> Checkout</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#tabel-monitor').DataTable();

    $('.checkout').click(function(){
      $('#modal-checkout').modal('show');
      let id = $(this).attr('kode-produk');
      $('#proses-checkout').attr('disabled','disabled');
      $('#content-checkout').html('Meload data...');
      $.post('cm-checkout.php', 'checkout=whatsapp&kode-produk='+id, function(respon){
        $('#content-checkout').html(respon);
        $('#proses-checkout').removeAttr('disabled');
        $('#proses-checkout').attr('kode-produk', id);
      })
    })

    $('#proses-checkout').click(function(){
      let tombol = $(this);
      let label = tombol.text();
      let kode_produk = $(this).attr('kode-produk');
      let perpanjang = $('#perpanjang-otomatis').is(':checked');
      tombol.attr('disabled','disabled');
      $.post('proses-data', 'checkout=true&kode-produk='+kode_produk+'&perpanjang='+perpanjang, function(respon){
        tombol.removeAttr('disabled');
        tombol.text(label);
        let pecah = respon.split('|');
        Swal.fire({
          position: "center",
          icon: pecah[0],
          title: pecah[1],
          showConfirmButton: false,
          timer: 2500
        });
        setTimeout(() => {
          if(pecah[0] == 'success'){
            $('#modal-checkout').modal('hide');
            window.location.reload();
          }
        }, 2600);
      })
    })

  })
</script>