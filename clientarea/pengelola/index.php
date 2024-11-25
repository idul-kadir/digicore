<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container pt-2">
      <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8" id="panel-koneksi"></div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function(){

        $('#panel-koneksi').load('panel-koneksi.php');
        setInterval(() => {
          $('#panel-koneksi').load('panel-koneksi.php');
        }, 60000);

      })
    </script>
  </body>
</html>