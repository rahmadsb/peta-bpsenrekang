<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
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
<div class="container-fluid">
  <h1><?= $title ?></h1>
  <?php if (in_array(session('role'), ['ADMIN', 'IPDS'])): ?>
    <a href="<?= base_url('sls/create') ?>" class="btn btn-primary mb-3">
      <i class="fas fa-plus"></i> Tambah SLS
    </a>
    <a href="<?= base_url('sls/export-excel') ?>" class="btn btn-success mb-3">
      <i class="fas fa-file-excel"></i> Ekspor Excel
    </a>
    <form action="<?= base_url('sls/import-excel') ?>" method="post" enctype="multipart/form-data" class="d-inline">
      <input type="file" name="excel_file" required>
      <button type="submit" class="btn btn-info mb-3">
        <i class="fas fa-file-import"></i> Impor Excel
      </button>
    </form>
  <?php endif; ?>
  <table id="slsTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Kode SLS</th>
        <th>Nama SLS</th>
        <th>Nama Desa</th>
        <th>Kecamatan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($sls as $row): ?>
        <tr>
          <td><?= esc($row['kode_sls']) ?></td>
          <td><?= esc($row['nama_sls']) ?></td>
          <td><?= esc($row['nama_desa']) ?></td>
          <td><?= esc($row['nama_kecamatan']) ?></td>
          <td>
            <a href="<?= base_url('sls/detail/' . $row['id']) ?>" class="btn btn-info btn-sm btn-action" title="Detail">
              <i class="fas fa-eye"></i>
            </a>
            <?php if (in_array(session('role'), ['ADMIN', 'IPDS'])): ?>
              <a href="<?= base_url('sls/edit/' . $row['id']) ?>" class="btn btn-warning btn-sm btn-action" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <form action="<?= base_url('sls/delete/' . $row['id']) ?>" method="post" class="d-inline delete-form">
                <button type="button" class="btn btn-danger btn-sm btn-delete btn-action" title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            <?php endif; ?>
          </td>
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
    $('#slsTable').DataTable();
    $('.btn-delete').on('click', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      Swal.fire({
        title: 'Yakin hapus data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>
<?php if (session()->getFlashdata('success')): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
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
      title: 'Gagal',
      text: '<?= session()->getFlashdata('error') ?>',
      timer: 2000,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>
<?php $this->endSection(); ?>