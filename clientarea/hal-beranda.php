<?php
session_start();
require 'function.php';
if(!isset($_SESSION['id'])){
  echo 'Silahkan keluar dan login lagi';
  exit;
}
$id = $_SESSION['id'];
$_SESSION['halaman'] = 'beranda';
?>
<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">
    
    <!-- Left side columns -->
    <div class="col-lg-8">
      <div class="row">
        
        <!-- Sales Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">
            
            <div class="card-body">
              <h5 class="card-title">Saldo</h5>
              
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fa-solid fa-rupiah-sign"></i>
                </div>
                <div class="ps-3">
                  <h6><?= str_replace('Rp ','',rupiah(pengguna($id)['saldo'])); ?></h6>
                </div>
              </div>
            </div>
            
          </div>
        </div><!-- End Sales Card -->
        
        <!-- Revenue Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">
            
            <div class="card-body">
              <h5 class="card-title">Layanan Aktif</span></h5>
              
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="ps-3">
                  <h6>2</h6>
                </div>
              </div>
            </div>
            
          </div>
        </div><!-- End Revenue Card -->
        
        <!-- Customers Card -->
        <div class="col-xxl-4 col-xl-12">
          
          <div class="card info-card customers-card">
            
            <div class="card-body">
              <h5 class="card-title">Layanan Tidak aktif</h5>
              
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fa-regular fa-circle-xmark"></i>
                </div>
                <div class="ps-3">
                  <h6>0</h6>
                </div>
              </div>
              
            </div>
          </div>
          
        </div><!-- End Customers Card -->
        
        <!-- layanan FAQ -->
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Layanan Pertanyaan</h5>
              <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                      Apa yang harus saya lakukan jika ingin menggunakan salah satu layanan DigiCore?
                    </button>
                  </h2>
                  <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                      Silahkan melakukan Top Up saldo minimal seharga layanan yang anda gunakan. Misalkan anda menggunakan layanan whatsapp kategori 1, maka anda melakukan top up minimal seharga Rp 70.000. Jika saldo anda sudah masuk, maka nominal saldo akan tampil dihalaman dashboard dan silahkan masuk ke menu layanan yang ingin anda gunakan 
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                      Bagaimana jika saya melakukan Top Up melebihi harga layanan yang saya gunakan?
                    </button>
                  </h2>
                  <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">Sisa saldo akan tersimpan di digicore dengan aman.</div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                      Beberapa layanan saya liat masih pre order.
                    </button>
                  </h2>
                  <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                      Kami akan memantau kesehatan server, ketersediaan layanan dari provider atau melakukan konfigurasi untuk mengaktifkan layanan. Tapi anda jangan khawatir, aktivasi layanan akan dihitung sejak layanan diaktivasi, bukan saat layanan di order.
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-headingEmpat">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEmpat" aria-expanded="false" aria-controls="flush-collapseThree">
                      Saya benar benar pemula dalm hal penggunaan API atau dalam proses pengelolaan server
                    </button>
                  </h2>
                  <div id="flush-collapseEmpat" class="accordion-collapse collapse" aria-labelledby="flush-headingEmpat" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                      Tim kami siap membantu dalam hal integrasi API Whatsapp, jaringan tunnel ataupun pengelolaan server. IT support kami bekerja senin sampai jumat dari pukul 08:00 sampai pukul 16:30 WITA. Selain dari jam kerja, kami tetap akan memberikan pelayanan dengan status slow respon <i class="bi emoji-wink"></i>
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-headinglima">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapselima" aria-expanded="false" aria-controls="flush-collapseThree">
                      Apakah kita bisa memesan sebuah layanan yang tidak ada dalam layanan yang tersedia?
                    </button>
                  </h2>
                  <div id="flush-collapselima" class="accordion-collapse collapse" aria-labelledby="flush-headinglima" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                      Yah bisa. Silahkan konsultasikan layanan yang anda butuhkan kepada IT Support kami. Kami ingin sekali bisa memecahkan semua persoalan IT yang anda hadapi
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- end FAQ -->
        
      </div>
    </div><!-- End Left side columns -->
    
    <!-- Right side columns -->
    <div class="col-lg-4">
      
      <!-- Recent Activity -->
      <div class="card">
        
        <div class="card-body">
          <h5 class="card-title">Cara melakukan Top Up Saldo</h5>
          
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                  Pembayaran via BCA
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Pembayaran via BCA bisa dilakukan melalui transfer ke rekening BCA <strong>7975437426</strong> an RIDWAN KADIR
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Pembayaran via QRIS
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <img src="assets/img/qris.jpeg" class="img-fluid" alt="gambar qris">
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Konfirmasi Pembayaran
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Setelah melakukan transfer Top Up baik melalui transfer ke nomor rekening BCA atau melakukan pembayaran melalui QRIS, silahkan melakukan konfirmasi ke whatsapp <strong>089669106718</strong> dengan mengirimkan foto sebagai bukti top up. Terima Kasih
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div><!-- End Recent Activity -->
      
      <!-- Website Traffic -->
      <div class="card">
        
        <div class="card-body pb-0">
          <h5 class="card-title">Server DigiCore</h5>
          <table class="table table-borderless">
            <tr>
              <td>Indonesia</td>
              <td><span class="badge rounded-pill bg-success"><i class="fa-regular fa-circle-dot fa-fade"></i> Online</span></td>
            </tr>
            <tr>
              <td>United States of America</td>
              <td><span class="badge rounded-pill bg-success"><i class="fa-regular fa-circle-dot fa-fade"></i> Online</span></td>
            </tr>
            <tr>
              <td>Singapore</td>
              <td><span class="badge rounded-pill bg-warning"><i class="fa-solid fa-triangle-exclamation fa-fade"></i> Maintenance</span></td>
            </tr>
            <tr>
              <td>France</td>
              <td><span class="badge rounded-pill bg-danger"><i class="fa-regular fa-circle-xmark fa-fade"></i> Offline</span></td>
            </tr>
          </table>
        </div><!-- End Website Traffic -->
        
      </div><!-- End Right side columns -->
      
    </div>
  </section>