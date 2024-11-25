<?php
session_start();
$_SESSION['halaman'] = 'whatsapp';
?>
<style>
  h5{
    font-family: arial, calibri, sans-serif;
  }
  ul > li{
    line-height:35px;
  }
</style>
<div class="row">
  <div class="col-sm-12 col-md-7 col-lg-7">
    <div class="row">

      <?php
        $data = json_decode(file_get_contents("whatsapp.json"), true);
        foreach($data['produk'] as $produk){
      ?>
      <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body text-center pt-4">
            <i class="fa-brands fa-whatsapp fa-3x text-center"></i>
            <h5 class="card-title text-center" style="padding-bottom:5px"><?= $produk['nama_produk'] ?></h5>
            <h5><span class="badge bg-success"><?= $produk['harga'] ?></span></h5>
            <hr style="margin-top:5px; margin-bottom:5px">
            <ul style="list-style-type: none; padding-left: 0; margin-top:20px">
              <?php
              for($i=0; $i < count($produk['syarat']['berlaku']); $i++){
                ?>
                <li><i class="fa-solid fa-check" style="color: #0fd23f;"></i> <?= $produk['syarat']['berlaku'][$i] ?></li>
                <?php
              }
              for($i=0; $i < count($produk['syarat']['tidak berlaku']); $i++){
                ?>
                <li><i class="fa-solid fa-xmark" style="color: #df2046;"></i> <?= $produk['syarat']['tidak berlaku'][$i] ?></li>
                <?php
              }
              ?>
            </ul>
            <button type="button" class="btn btn-primary"><i class="fa-solid fa-cart-shopping"></i> Pesan</button>
          </div>
        </div>
      </div>
      <?php } ?>

    </div>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5">
    <div class="card">
      <h5 class="card-header">Layanan Aktif</h5>
      <div class="card-body">
        <div class="accordion accordion-flush" id="accordionFlushExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingOne">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                OTP
              </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                <div class="card">
                  <div class="card-header">
                    <i class="fa-solid fa-file-contract"></i> Dokumentasi API
                  </div>
                  <div class="card-body">
                    <h6 class="card-title">API untuk mengirim pesan</h6>
                    <code>
                      // Ini adalah contoh kode JavaScript
                      function helloWorld() {
                          console.log('Hello, World!');
                      }

                      helloWorld();
                      </code>
                    <hr>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <i class="fa-solid fa-tv"></i> Monitoring
                  </div>
                  <div class="card-body">
                    <table class="table" id="tabel-monitor">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">First</th>
                          <th scope="col">Last</th>
                          <th scope="col">Handle</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td>Mark</td>
                          <td>Otto</td>
                          <td>@mdo</td>
                        </tr>
                        <tr>
                          <th scope="row">2</th>
                          <td>Jacob</td>
                          <td>Thornton</td>
                          <td>@fat</td>
                        </tr>
                      </tbody>
                    </table>
                    <hr>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Laporan
              </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                Accordion Item #3
              </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#tabel-monitor').DataTable()
  })
</script>