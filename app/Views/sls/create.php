<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid">
  <h1>Tambah SLS</h1>
  <form id="form-tambah-sls" action="<?= base_url('sls/store') ?>" method="post">
    <div class="form-group">
      <label>Kode SLS</label>
      <input type="text" name="kode_sls" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Nama SLS</label>
      <input type="text" name="nama_sls" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Kode Desa</label>
      <input type="text" name="kode_desa" class="form-control">
    </div>
    <div class="form-group">
      <label>Nama Desa</label>
      <input type="text" name="nama_desa" class="form-control">
    </div>
    <div class="form-group">
      <label>Kecamatan</label>
      <input type="text" name="nama_kecamatan" class="form-control">
    </div>
    <div class="form-group">
      <label>Kabupaten</label>
      <input type="text" name="nama_kabupaten" class="form-control">
    </div>
    <div class="form-group">
      <label>Provinsi</label>
      <input type="text" name="nama_provinsi" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('sls') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>
<!-- jQuery -->
<script src=<?= base_url("plugins/jquery/jquery.min.js") ?>></script>
<!-- jQuery UI 1.11.4 -->
<script src=<?= base_url("plugins/jquery-ui/jquery-ui.min.js") ?>></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src=<?= base_url("plugins/bootstrap/js/bootstrap.bundle.min.js") ?>></script>
<script src=<?= base_url("plugins/chart.js/Chart.min.js") ?>></script>
<script src=<?= base_url("plugins/sparklines/sparkline.js") ?>></script>
<script src=<?= base_url("plugins/jqvmap/jquery.vmap.min.js") ?>></script>
<script src=<?= base_url("plugins/jqvmap/maps/jquery.vmap.usa.js") ?>></script>
<script src=<?= base_url("plugins/jquery-knob/jquery.knob.min.js") ?>></script>
<script src=<?= base_url("plugins/moment/moment.min.js") ?>></script>
<script src=<?= base_url("plugins/daterangepicker/daterangepicker.js") ?>></script>
<script src=<?= base_url("plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") ?>></script>
<script src=<?= base_url("plugins/summernote/summernote-bs4.min.js") ?>></script>
<script src=<?= base_url("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js") ?>></script>
<script src=<?= base_url("js/adminlte.js") ?>></script>
<script src=<?= base_url("js/pages/dashboard.js") ?>></script>
<script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>
  $(function() {
    $('#form-tambah-sls').on('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Simpan data?',
        text: 'Pastikan data sudah benar sebelum disimpan!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
</script>
<?php $this->endSection(); ?>