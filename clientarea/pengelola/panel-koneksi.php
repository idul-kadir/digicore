<?php
require '../function.php';

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

$b_server1 = json_decode(file_get_contents("https://api.digicore.web.id/backup_server1.json"), true);
if($b_server1['status'] == 'connected'){
  $b_icon1 = '<i class="fa-solid fa-circle fa-fade float-end mt-1" style="color: #00cc29;"></i>';
}else{
  $b_icon1 = '<i class="fa-solid fa-circle-xmark fa-beat-fade float-end" style="color: #ca0202;"></i>';
}

$b_server2 = json_decode(file_get_contents("https://api.digicore.web.id/backup_server2.json"), true);
if($b_server2['status'] == 'connected'){
  $b_icon2 = '<i class="fa-solid fa-circle fa-fade float-end mt-1" style="color: #00cc29;"></i>';
}else{
  $b_icon2 = '<i class="fa-solid fa-circle-xmark fa-beat-fade float-end" style="color: #ca0202;"></i>';
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
          <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $server1['timestamp'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $server1['timestamp']).'</span>' ?></p>
          <p>Nomor : +6285240346020</p>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <h5 class="card-header">SERVER 2 <?= $icon2; ?></h5>
        <div class="card-body">
          <p>Lokasi server : Indonesia - Depok</p>
          <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $server2['timestamp'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $server2['timestamp']).'</span>' ?></p>
          <p>Nomor : +6287840189270</p>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <h5 class="card-header">BACKUP SERVER<?= $b_icon1; ?></h5>
        <div class="card-body">
          <p>Lokasi server : Indonesia - LABKOM 2</p>
          <p>Terakhir dipantau <?= tgl_indo(date('Y-m-d', $b_server2['timestamp'])).' Pukul <span class="badge bg-dark">'. date('H:i:s', $b_server2['timestamp']).'</span>' ?></p>
          <a href="https://backup.digicore.web.id/start-session?session=server1&scan=true" target="_blank">Backup 1</a> | <a href="https://backup.digicore.web.id/start-session?session=server2&scan=true" target="_blank">Backup 2</a>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3"></div>
  </div>
  <hr>
</div>