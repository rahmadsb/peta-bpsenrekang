<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<style>
  /* Custom styling for action buttons */
  .btn-action {
    margin-right: 2px;
    transition: all 0.2s ease-in-out;
  }

  .btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .btn-action i {
    font-size: 0.875rem;
  }
</style>
<div class="container mt-4">
  <h2>Manajemen Kegiatan</h2>
  <?php if ($canManage): ?>
    <a href="<?= base_url('kegiatan/create') ?>" class="btn btn-primary mb-3">
      <i class="fas fa-plus"></i> Tambah Kegiatan
    </a>
  <?php endif; ?>
  <table class="table table-bordered" id="table">
    <thead>
      <tr>
        <th>Opsi Kegiatan</th>
        <th>Tahun</th>
        <th>Bulan</th>
        <th>Tanggal Batas Cetak</th>
        <th>Status</th>
        <?php if ($canManage): ?><th>Aksi</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($kegiatan as $item): ?>
        <tr>
          <td><?= esc($opsiMap[$item['id_opsi_kegiatan']] ?? '-') ?></td>
          <td><?= esc($item['tahun']) ?></td>
          <td><?= esc($item['bulan']) ?></td>
          <td><?= esc($item['tanggal_batas_cetak']) ?></td>
          <td><?= esc($item['status']) ?></td>
          <?php if ($canManage): ?>
            <td>
              <a href="<?= base_url('kegiatan/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning btn-action" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <a href="<?= base_url('kelola-peta-wilkerstat/' . $item['id']) ?>" class="btn btn-sm btn-info btn-action" title="Kelola Peta Wilkerstat">
                <i class="fas fa-map"></i>
              </a>
              <a href="#" class="btn btn-sm btn-danger btn-delete btn-action" data-url="<?= base_url('kegiatan/delete/' . $item['id']) ?>" title="Hapus">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<!-- jQuery -->
<script src=<?= base_url("plugins/jquery/jquery.min.js") ?>></script>
<!-- jQuery UI 1.11.4 -->
<script src=<?= base_url("plugins/jquery-ui/jquery-ui.min.js") ?>></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src=<?= base_url("plugins/bootstrap/js/bootstrap.bundle.min.js") ?>></script>
<!-- ChartJS -->
<script src=<?= base_url("plugins/chart.js/Chart.min.js") ?>></script>
<!-- Sparkline -->
<script src=<?= base_url("plugins/sparklines/sparkline.js") ?>></script>
<!-- JQVMap -->
<script src=<?= base_url("plugins/jqvmap/jquery.vmap.min.js") ?>></script>
<script src=<?= base_url("plugins/jqvmap/maps/jquery.vmap.usa.js") ?>></script>
<!-- jQuery Knob Chart -->
<script src=<?= base_url("plugins/jquery-knob/jquery.knob.min.js") ?>></script>
<!-- daterangepicker -->
<script src=<?= base_url("plugins/moment/moment.min.js") ?>></script>
<script src=<?= base_url("plugins/daterangepicker/daterangepicker.js") ?>></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src=<?= base_url("plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") ?>></script>
<!-- Summernote -->
<script src=<?= base_url("plugins/summernote/summernote-bs4.min.js") ?>></script>
<!-- overlayScrollbars -->
<script src=<?= base_url("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js") ?>></script>
<!-- AdminLTE App -->
<script src=<?= base_url("js/adminlte.js") ?>></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src=<?= base_url("js/pages/dashboard.js") ?>></script>
<!-- Datatables -->
<script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url('plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>
  $(document).ready(function() {
    $('#table').DataTable();
    $('.btn-delete').on('click', function(e) {
      e.preventDefault();
      var url = $(this).data('url');
      Swal.fire({
        title: 'Yakin ingin menghapus kegiatan ini?',
        text: "Tindakan ini tidak bisa dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
</script>
<?php if (session()->getFlashdata('success')): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Sukses!',
      text: '<?= session()->getFlashdata('success') ?>',
      timer: 2000,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '<?= session()->getFlashdata('error') ?>',
      timer: 2500,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>
<?= $this->endSection() ?>