<?php
session_start();
if(!isset($_SESSION['id'])){
  exit;
}
require 'function.php';
$id = $_SESSION['id'];
$_SESSION['halaman'] = 'konsultasi';
?>
<div class="card">
  <div class="card-body">
    <h1 class="mb-4 mt-3">Daftar Ticket</h1>
    <div class="row mb-3">
      <div class="col-md-auto">
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createTicketModal">Buat Ticket Baru</button>
      </div>
      <div class="col-md-6">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari ticket...">
      </div>
      <div class="col-md-2">
        <select id="statusFilter" class="form-select">
          <option value="">Semua Status</option>
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
          <option value="Pending">Pending</option>
        </select>
      </div>
      <div class="col-md-2">
        <button id="resetFilter" class="btn btn-secondary w-100">Reset Filter</button>
      </div>
    </div>
    <div class="row">
      <?php
      $cek_tiket = query("SELECT * FROM `tiket` WHERE id_user = '$id' ");
      if(mysqli_num_rows($cek_tiket)>0){
      ?>
      <div class="table-responsive">
        <table id="ticketTable" class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Judul Ticket</th>
              <th>Status</th>
              <th>Tanggal Dibuat</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach($cek_tiket as $data){
              if($data['status'] == 'open'){
                $s_badge = 'bg-success';
              }elseif($data['status'] == 'close'){
                $s_badge = 'bg-primary';
              }else{
                $s_badge = 'bg-info';
              }
            ?>
            <tr>
              <td>#<?= substr($data['id'],0,4); ?></td>
              <td><?= $data['judul'] ?></td>
              <td><span class="badge <?= $s_badge ?>"><?= ucwords($data['status']); ?></span></td>
              <td><?= tgl_indo(date('Y-m-d', $data['id'])) ?></td>
              <td><a href="#" class="btn btn-sm btn-info btn-view" id-ticket="<?= $data['id'] ?>">Lihat</a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <?php 
      }else{
      ?>
      <div class="alert alert-success" role="alert">
        Tidak ada riwayat tiket
      </div>
      <?php
        } 
      ?>
    </div>
  </div>
</div>

<!-- Modal untuk Buat Ticket Baru -->
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="ticketForm">
        <input type="text" value="<?= time(); ?>" name="new-tiket" hidden>
        <div class="modal-header">
          <h5 class="modal-title" id="createTicketModalLabel">Buat Ticket Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="ticketTitle" class="form-label">Judul Ticket</label>
            <input type="text" class="form-control" id="ticketTitle" name="judul" placeholder="Ubah paket Hosting" required>
          </div>
          <div class="mb-3">
            <label for="ticketCategory" class="form-label">Kategori Masalah</label>
            <select class="form-select" id="ticketCategory" name="kategori" required>
              <option value="">Pilih Kategori</option>
              <option value="Billing">Tagihan</option>
              <option value="Technical">Teknis</option>
              <option value="General">Umum</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="ticketPriority" class="form-label">Prioritas</label>
            <select class="form-select" id="ticketPriority" name="prioritas" required>
              <option value="">Pilih Prioritas</option>
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="ticketDescription" class="form-label">Deskripsi Masalah</label>
            <textarea class="form-control" id="ticketDescription" rows="5" name="deskripsi" placeholder="Jelaskan masalah Anda" required></textarea>
          </div>
          <div class="mb-3">
            <label for="ticketAttachment" class="form-label">Lampiran</label>
            <input type="file" class="form-control" id="ticketAttachment" name="lampiran" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" form="ticketForm" id="tbl-submit-tiket" class="btn btn-primary">Buat Ticket</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal untuk Detail Tiket -->
<div class="modal fade" id="ticketDetailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ticketDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketDetailModalLabel">Detail Tiket #<span id="ticketId"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="content-detail">
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
      // Inisialisasi DataTables
      var table = $('#ticketTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "lengthChange": false,
        "dom": 'lrtip',
        "info": false,
        "dom": '<"top"i>rt<"bottom"p><"clear">',
        "language": {
          "search": "Cari:",
          "paginate": {
            "previous": "Sebelumnya",
            "next": "Selanjutnya"
          }
        }
      });

      // Filter berdasarkan status
      $('#statusFilter').on('change', function() {
        var status = $(this).val();
        table.column(2).search(status).draw();
      });

      // Reset filter
      $('#resetFilter').on('click', function() {
        $('#statusFilter').val('');
        table.search('').columns().search('').draw();
      });

      // Pencarian manual
      $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
      });

      // Event listener untuk tombol "Lihat"
      $('#ticketTable').on('click','.btn-view',function() {
        let idTicket = $(this).attr('id-ticket');
        // Tampilkan modal
        $('#ticketDetailModal').modal('show');
        $('#content-detail').html('<h5>Meload data...</h5>')
        $.post('ajax','detail-tiket='+ idTicket, function(respon){
          $('#content-detail').html(respon);
        })
      });

      $('#ticketForm').submit(function(e){
        e.preventDefault();
        let data = new FormData(this);
        $.ajax({
          beforeSend: function(){
            $('#tbl-submit-tiket').attr('disabled', true);
            $('#tbl-submit-tiket').text('Kirim tiket...');
          },
          url: 'proses-data',
          type: 'post',
          data: data,
          processData: false,
          contentType: false,
          success: function(respon){
            let pecah = respon.split('|');
            Swal.fire({
              position: "center",
              icon: pecah[0],
              title: pecah[1],
              showConfirmButton: false,
              timer: 3000
            });
            setTimeout(() => {
              $('#createTicketModal').modal('hide');
            }, 3500);
            setTimeout(() => {
                location.reload();
              }, 4000);
          }
        })
      })

      
    });
  </script>