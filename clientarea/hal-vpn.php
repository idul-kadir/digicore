<?php
session_start();
if(!isset($_SESSION['id'])){
  exit;
}
$id = $_SESSION['id'];
$_SESSION['halaman'] = 'vpn';
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
  <div class="col-sm-12 col-md-6">
    <?php
    $list_vpn = query("SELECT * FROM `l_vpn` WHERE id_user = '$id' ");
    while($data=mysqli_fetch_assoc($list_vpn)){
      $produk = produk($data['kode_produk']);
      $konektor1 = detail_connector($data['konektor1']);
      $konektor2 = detail_connector($data['konektor2']);
    ?>
    <div class="card">
      <h6 class="card-header text-white bg-primary"><?= $produk['nama'] ?></h6>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12 col-lg-7">
            <?php
            $id_vpn = $data['id'];
            $list_vpn = query("SELECT * FROM `tunnel` WHERE id_vpn = '$id_vpn' ");
            $pecah = explode(' ',$produk['nama']);
            for($i=0; $i<$pecah[1]; $i++){
              $vpn = mysqli_fetch_assoc($list_vpn);
              if($vpn){
                $dst_port = $vpn['dst_port'];
                $id_tunnel = $vpn['id'];
              }else{
                $dst_port = '';
                $id_tunnel = '';
              }
            ?>
            <form action="#" class="tambah-firewall" id-vpn="<?= $data['id'] ?>">
              <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-7 pt-2">
                  <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Pilih IP</label>
                    <select class="form-select" aria-label="Default select example" name="ip-vpn" required>
                      <?php
                      if($vpn['ip'] == $konektor1['ip']){
                        ?>
                      <option value="<?= $konektor1['ip'] ?>"><?= $konektor1['ip'].' - '.$konektor1['jenis'] ?></option>
                      <option value="<?= $konektor2['ip'] ?>"><?= $konektor2['ip'].' - IP VPN' ?></option>
                        <?php
                      }else{
                        ?>
                      <option value="<?= $konektor2['ip'] ?>"><?= $konektor2['ip'].' - IP VPN' ?></option>
                      <option value="<?= $konektor1['ip'] ?>"><?= $konektor1['ip'].' - '.$konektor1['jenis'] ?></option>
                        <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-5 pt-2">
                  <div class="mb-3">
                    <label for="dst-port" class="form-label">Dst Port</label>
                    <input type="number" class="form-control" id="dst-port" name="dst-port" value="<?= $dst_port ?>">
                  </div>
                </div>
                <div class="d-grid gap-2">
                  <button class="btn btn-primary id-tunnel" type="submit" id-tunnel="<?= $id_tunnel ?>"><i class="fa-solid fa-satellite-dish"></i> Aktifkan</button>
                </div>
              </div>
            </form>
            <?php
            if($vpn){
              ?>
            <p class="mt-1">Gunakan <b><?= ip_public()['dns'] ?></b> dengan port <b><i><?= $vpn['src_port']; ?></i></b> untuk mengakses port <?= $vpn['dst_port'] ;?> dijaringan lokal anda</p>
              <?php
            }
              if($pecah[1] > 1){
                echo '<hr>';
              }
            } 
            ?>
          </div>
          <div class="col-sm-12 col-lg-5 pt-2">
            <table class="table table-bordered">
              <tr>
                <td colspan="2">L2TP, PPTP, OPEN VPN</td>
              </tr>
              <tr>
                <td>IP VPN</td>
                <td><?= $konektor2['ip'] ?></td>
              </tr>
              <tr>
                <td>Username</td>
                <td><?= $konektor2['catatan'] ?></td>
              </tr>
              <tr>
                <td>Password</td>
                <td><?= $konektor2['catatan'] ?></td>
              </tr>
              <tr>
                <td colspan="2"><a href="assets/konektor/config-digicore.ovpn" target="_blank">Download File config Open VPN</a></td>
              </tr>
              <tr>
                <td colspan="2"><a href="<?= $konektor1['config'] ?>" target="_blank">Download File config Wireguard</a></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="col-sm-12 col-md-6">
    <div class="row">

    <?php
        $result = query("SELECT * FROM `produk` WHERE kategori = 'VPN Tunnel' ORDER BY harga ASC ");
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

    $(document).on('submit','.tambah-firewall',function(e){
      e.preventDefault();
      $(e.originalEvent.submitter).attr('disabled', true);
      $(e.originalEvent.submitter).text('Menambahkan Firewall...');
      let id_tunnel = $(e.originalEvent.submitter).attr('id-tunnel')
      let id_vpn = $(this).attr('id-vpn');
      let data = $(this).serialize();
      $.post('proses-data', 'tambah-firewall='+id_vpn+'&id-tunnel='+id_tunnel+'&'+data, function(respon){
        let pecah = respon.split('|');
        Swal.fire({
          position: "center",
          icon: pecah[0],
          title: pecah[1],
          showConfirmButton: false,
          timer: 2500
        });
        if(pecah[0] == 'success'){
          setTimeout(() => {
            location.reload();
          }, 2600);
        }
      })
    });

    $('.tambah-firewall').submit(function(e){
    })
    
  })
</script>