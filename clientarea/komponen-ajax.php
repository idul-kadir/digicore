<?php
session_start();
if(!isset($_SESSION['id'])){
  exit;
}
require 'function.php';
$id = $_SESSION['id'];

$pengguna = pengguna($id);

if(isset($_POST['detail-tiket'])){
  $id_ticket = bersihkan($_POST['detail-tiket']);
  $cek_tiket = query("SELECT * FROM `tiket` WHERE id = '$id_ticket' ");
  if(mysqli_num_rows($cek_tiket)<1){
    echo '<h5>Permintaan anda telah dimanipulasi. Silahkan logout dan login kembali</h5>';
    exit;
  }
  $data = mysqli_fetch_assoc($cek_tiket);
  if($data['status'] == 'open'){
    $s_badge = 'bg-success';
  }elseif($data['status'] == 'close'){
    $s_badge = 'bg-primary';
  }else{
    $s_badge = 'bg-info';
  }

  if($data['prioritas'] == 'High'){
    $badge_p = 'bg-danger';
  }elseif($data['prioritas'] == 'Medium'){
    $badge_p = 'bg-warning';
  }else{
    $badge_p = 'bg-primary';
  }
  ?>
<style>
  .card-body{
    padding: 25px;
  }
  #ticketConversation {
    overflow-y: auto;
    background-color: aliceblue;
    padding-left: 5px;
    padding-right: 5px;
  }
</style>
<!-- Informasi Utama -->
<div class="mb-4">
  <h5>Judul: <?= $data['judul']; ?></h5>
  <p><strong>Status:</strong> <span class="badge <?= $s_badge ?>"><?= ucfirst($data['status']); ?></span></p>
  <p><strong>Tanggal Dibuat:</strong> <?= tgl_indo(date('Y-m-d', $data['id'])). ' '. date('H:i:s', $data['id']) ?> WITA</p>
  <p><strong>Prioritas:</strong> <span class="badge <?= $badge_p ?>"><?= $data['prioritas'] ?></span></p>
</div>

<hr>
<!-- Percakapan (Scrollable) -->
<div class="mb-4 pt-4" id="ticketConversation">
  <!-- Percakapan Client -->
  <div class="card mb-3 bg-light border-start border-primary ms-auto" style="max-width: 80%;">
    <div class="card-body d-flex flex-column">
      <p class="mb-1"><strong><?= $pengguna['nama'] ?>:</strong> <?= $data['deskripsi'] ?></p>
      <small class="text-muted ms-auto"><?= date('m-d-Y', $data['id']). ' ' .date('H:i', $data['id']) ?></small>
    </div>
  </div>
  <?php
  $list_percakapan = query("SELECT * FROM `percakapan` WHERE id_topik = '$id_ticket' ");
  foreach($list_percakapan as $percakapan){
    if($percakapan['id_user'] == $id){
  ?>
    <!-- Percakapan Client -->
    <div class="card mb-3 bg-light border-start border-primary ms-auto" style="max-width: 80%;">
      <div class="card-body d-flex flex-column">
        <p class="mb-1"><strong><?= $pengguna['nama'] ?>:</strong> <?= $percakapan['teks'] ?></p>
        <small class="text-muted ms-auto"><?= date('m-d-Y', $percakapan['id']). ' ' .date('H:i', $percakapan['id']) ?></small>
      </div>
    </div>
  <?php
    }else{
  ?>
    <!-- Percakapan Support -->
    <div class="card mb-3 bg-light border-start border-success me-auto" style="max-width: 80%;">
      <div class="card-body d-flex flex-column">
        <p class="mb-1"><strong>Support:</strong> <?= $percakapan['teks']; ?></p>
        <small class="text-muted ms-auto"><?= date('m-d-Y', $percakapan['id']). ' ' .date('H:i', $percakapan['id']) ?></small>
      </div>
    </div>
  <?php 
    }
  } 
  ?>
  <!-- Tambahkan percakapan lainnya di sini -->
</div>
<hr>
<!-- Form Balas -->
<div>
  <h5>Balas Tiket</h5>
  <form id="replyForm">
    <input type="text" value="<?= $id_ticket ?>" name="id-ticket" hidden>
    <div class="mb-3">
      <textarea class="form-control" name="deskripsi" id="replyMessage" rows="3" placeholder="Tulis balasan Anda di sini..."></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Kirim Balasan</button>
  </form>
</div>
<?php
}
?>
<script>
  $(document).ready(function(){
    $('#replyForm').submit(function(e){
      e.preventDefault();
      let id = $('[name="id-ticket"]').val();
      let data = $(this).serialize();
      $.post('proses-data','reply-ticket=true&'+data, function(respon){
        let pecah = respon.split('|');
        Swal.fire({
          position: "center",
          icon: pecah[0],
          title: pecah[1],
          showConfirmButton: false,
          timer: 2500
        });
      })
    })
  })
</script>