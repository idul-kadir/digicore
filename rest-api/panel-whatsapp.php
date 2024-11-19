<?php
require 'fungsi.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>DigiCore - Panel Whatsapp</title>
  </head>
  <body>
<?php
$server1 = json_decode(file_get_contents("https://api.digicore.web.id/server1.json"), true);
if($server1['status'] == 'connected'){
  $icon1 = '<i class="fa-solid fa-circle fa-fade float-end mt-1" style="color: #00cc29;"></i>';
}else{
  $icon1 = '<i class="fa-solid fa-circle-xmark fa-beat-fade float-end" style="color: #ca0202;"></i>';
}

$server2 = json_decode(file_get_contents("https://api.digicore.web.id/server2.json"), true);
if($server2['status'] == 'connected'){
  $icon2 = '<i class="fa-solid fa-circle fa-fade float-end mt-1" style="color: #00cc29;"></i>';
}else{
  $icon2 = '<i class="fa-solid fa-circle-xmark fa-beat-fade float-end" style="color: #ca0202;"></i>';
}
?>
    <div class="container">
      <div class="row pt-2">
        <div class="col-sm-12 col-md-6 col-lg-3">
          <div class="card h-100">
            <h5 class="card-header">SERVER 1 <?= $icon1; ?></h5>
            <div class="card-body">
              <p>Lokasi server : United State - New York</p>
              <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $server1['waktu'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $server1['waktu']).'</span>' ?></p>
              <p>Nomor : +6285240346020</p>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
          <div class="card h-100">
            <h5 class="card-header">SERVER 2 <?= $icon2; ?></h5>
            <div class="card-body">
              <p>Lokasi server : Indonesia - Gorontalo</p>
              <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $server2['waktu'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $server2['waktu']).'</span>' ?></p>
              <p>Nomor : +6287840189270</p>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3"></div>
        <div class="col-sm-12 col-md-6 col-lg-3"></div>
      </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>