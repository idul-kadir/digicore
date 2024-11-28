<?php
session_start();
if(!isset($_SESSION['pengelola'])){
  header("location: ../login");
  exit;
}
require '../function.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/img/logo.png" rel="icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <title>Pengelola</title>
    <style>
      html, body{
        background-color: #ededed;
      }
      .container{
        background-color: white;
      }
    </style>
  </head>
  <body>
    <div class="container pt-2 pb-2">
      <center><h2><i class="fa-solid fa-gear fa-spin" style="color: #01090e;"></i> Panel Kontrol Layanan DIGICORE <i class="fa-solid fa-gear fa-spin fa-spin-reverse" style="color: #01090e;"></i></h2></center>
      <hr>
      <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8">
          <div class="row" id="panel-koneksi"></div>

          <center><h4>Produk</h4></center>
          <button type="button" class="btn btn-primary btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#modal-tambah-produk">Tambah Produk</button>
          <table class="table" id="produk">
            <thead>
              <tr class="table-dark">
                <th scope="col" width="25px">#</th>
                <th scope="col">Produk</th>
                <th scope="col">Kategori</th>
                <th scope="col">Harga</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $result = query("SELECT * FROM `produk`");
              foreach($result as $data){
              ?>
              <tr>
                <th scope="row"><?= $no++; ?></th>
                <td><?= $data['nama'] ?></td>
                <td><?= $data['kategori'] ?></td>
                <td><?= $data['harga'] ?></td>
                <td><?= $data['status'] ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <hr>

          <center><h4>Layanan</h4></center>
          <div class="row mt-4">
          <?php
          for($i=0; $i<11; $i++){
          ?>
            <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
              <div class="card">
                <h5 class="card-header">Featured</h5>
                <div class="card-body">
                  <h5 class="card-title">Special title treatment</h5>
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">

          <div class="card">
            <h5 class="card-header">Client</h5>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Expired</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $result = json_decode(file_get_contents("https://api.digicore.web.id/get-client"), true);
                  foreach($result['data'] as $data){
                  ?>
                  <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= $data['nama'] ?></td>
                    <td><?= $data['kadaluarsa'] ?></td>
                    <td>
                      <?php
                      if($data['status'] == 'aktif'){
                        echo '<span class="badge rounded-pill bg-success">Aktif</span>';
                      }else{
                        echo '<span class="badge rounded-pill bg-danger">Kadaluarsa</span>';
                      }
                      ?>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card mt-2">
            <h5 class="card-header">Tambah Saldo</h5>
            <div class="card-body">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Client</label>
                <select class="form-select" aria-label="Default select example">
                  <option selected value="">Pilih Client</option>
                  <?php
                  foreach($result['data'] as $data){
                  ?>
                  <option value="<?= $data['id'] ?>"><?= $data['nama'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="number" class="form-control" id="saldo">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-tambah-produk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Tambah Produk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" id="form-tambah-produk">
            <div class="modal-body">
              <div class="mb-3">
                <label for="kode-produk" class="form-label">Kode Produk</label>
                <input type="text" name="kode" class="form-control" id="kode-produk" required placeholder="OTP 1">
              </div>
              <div class="mb-3">
                <label for="nama-produk" class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" id="nama-produk" placeholder="OTP" required>
              </div>
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Kategori</label>
                <select class="form-select" aria-label="Default select example" name="kategori" required>
                  <option selected value="">Pilih Kategori</option>
                  <option value="Whatsapp">Whatsapp</option>
                  <option value="IP Public">IP Public</option>
                  <option value="VPN Tunnel">VPN Tunnel</option>
                  <option value="Docker">Docker</option>
                  <option value="Domain">Domain</option>
                  <option value="Hosting">Hosting</option>
                  <!-- <option value=""></option> -->
                </select>
              </div>
              <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" placeholder="30000" name="harga" required>
              </div>
              <div class="mb-3">
                <label for="syarat-terima" class="form-label">S&K Terima</label>
                <textarea class="form-control" id="syarat-terima" rows="3" name="s-k-terima"></textarea>
              </div>
              <div class="mb-3">
                <label for="syarat-tolak" class="form-label">S&K Tolak</label>
                <textarea class="form-control" id="syarat-tolak" rows="3" name="s-k-tolak"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="tbl-tambah-produk">Tambah Produk</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      $(document).ready(function(){

        new DataTable('#produk');

        $('#panel-koneksi').load('panel-koneksi.php');
        setInterval(() => {
          $('#panel-koneksi').load('panel-koneksi.php');
        }, 60000);

        $('#form-tambah-produk').submit(function(e){
          e.preventDefault();
          $('#tbl-tambah-produk').attr('disabled','disabled');
          $('#tbl-tambah-produk').text('Menambah produk...');
          let data = $(this).serialize();
          $.post('proses.php','tambah-produk=true&'+data, function(respon){
            $('#tbl-tambah-produk').removeAttr('disabled');
            $('#tbl-tambah-produk').text('Tambah Produk');
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
                $('#modal-tambah-produk').modal('hide');
              }, 2600);
              setTimeout(() => {
                location.reload()
              }, 2800);
            }
          })
        })

      })
    </script>
  </body>
</html>