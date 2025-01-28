<?php
require '../function.php';

$server1 = json_decode(file_get_contents("https://api.digicore.web.id/status-server/server1.json"), true);
if($server1['status'] == 'connected'){
  $icon1 = '<i class="fa-solid fa-circle fa-fade float-end mt-1" style="color: #00cc29;"></i>';
}else{
  $icon1 = '<i class="fa-solid fa-circle-xmark fa-beat-fade float-end" style="color: #ca0202;"></i>';
}

$server2 = json_decode(file_get_contents("https://api.digicore.web.id/status-server/server2.json"), true);
if($server2['status'] == 'connected'){
  $icon2 = '<i class="fa-solid fa-circle fa-fade float-end mt-1" style="color: #00cc29;"></i>';
}else{
  $icon2 = '<i class="fa-solid fa-circle-xmark fa-beat-fade float-end" style="color: #ca0202;"></i>';
}

$server3 = json_decode(file_get_contents("https://api.digicore.web.id/status-server/server3.json"), true);
if($server3['status'] == 'connected'){
  $icon2 = '<i class="fa-solid fa-circle fa-fade float-end mt-1" style="color: #00cc29;"></i>';
}else{
  $icon2 = '<i class="fa-solid fa-circle-xmark fa-beat-fade float-end" style="color: #ca0202;"></i>';
}
?>
<div class="container">
  <center><h4>Status Koneksi Whatsapp</h4></center>
  <div class="row mt-4">
    <div class="col-sm-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <h5 class="card-header">SERVER 1 <?= $icon1; ?></h5>
        <div class="card-body">
          <p>Lokasi server : United State - New York</p>
          <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $server1['last_update'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $server1['last_update']).'</span>' ?></p>
          <p>Nomor : +<?= $server1['number'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <h5 class="card-header">SERVER 2 <?= $icon2; ?></h5>
        <div class="card-body">
          <p>Lokasi server : Indonesia - Sipatana</p>
          <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $server2['last_update'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $server2['last_update']).'</span>' ?></p>
          <p>Nomor : +<?= $server2['number'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <h5 class="card-header">SERVER 3 <?= $icon2; ?></h5>
        <div class="card-body">
          <p>Lokasi server : Singapore</p>
          <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $server3['last_update'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $server3['last_update']).'</span>' ?></p>
          <p>Nomor : +<?= $server3['number'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3"></div>
  </div>
  <hr>
</div>