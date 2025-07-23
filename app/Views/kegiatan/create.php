<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
  <h2>Tambah Kegiatan</h2>
  <form action="<?= base_url('kegiatan/store') ?>" method="post">
    <div class="mb-3">
      <label for="kode_kegiatan_option" class="form-label">Opsi Kegiatan</label>
      <select class="form-control<?= isset($validation) && $validation->hasError('kode_kegiatan_option') ? ' is-invalid' : '' ?>" id="kode_kegiatan_option" name="kode_kegiatan_option" required>
        <option value="">Pilih Opsi Kegiatan</option>
        <?php foreach ($opsi as $o): ?>
          <option value="<?= $o['uuid'] ?>" <?= old('kode_kegiatan_option') == $o['uuid'] ? 'selected' : '' ?>><?= esc($o['nama_kegiatan']) ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (isset($validation) && $validation->hasError('kode_kegiatan_option')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('kode_kegiatan_option') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="tahun" class="form-label">Tahun</label>
      <input type="number" class="form-control<?= isset($validation) && $validation->hasError('tahun') ? ' is-invalid' : '' ?>" id="tahun" name="tahun" value="<?= old('tahun', date('Y')) ?>" required>
      <?php if (isset($validation) && $validation->hasError('tahun')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('tahun') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="bulan" class="form-label">Bulan</label>
      <select class="form-control<?= isset($validation) && $validation->hasError('bulan') ? ' is-invalid' : '' ?>" id="bulan" name="bulan" required>
        <option value="">Pilih Bulan</option>
        <?php foreach ($bulanList as $bulan): ?>
          <option value="<?= $bulan ?>" <?= old('bulan', date('F')) == $bulan ? 'selected' : '' ?>><?= $bulan ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (isset($validation) && $validation->hasError('bulan')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('bulan') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="tanggal_batas_cetak" class="form-label">Tanggal Batas Cetak</label>
      <input type="text" class="form-control<?= isset($validation) && $validation->hasError('tanggal_batas_cetak') ? ' is-invalid' : '' ?>" id="tanggal_batas_cetak" name="tanggal_batas_cetak" value="<?= old('tanggal_batas_cetak') ?>" required autocomplete="off">
      <?php if (isset($validation) && $validation->hasError('tanggal_batas_cetak')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('tanggal_batas_cetak') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label class="form-label">Status</label>
      <input type="text" class="form-control" value="<?php
                                                      $role = session('role');
                                                      echo $role === 'SUBJECT_MATTER' ? 'digunakan (SM)' : 'disiapkan (IPDS)';
                                                      ?>" readonly>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('kegiatan') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>
<!-- Date Range Picker -->
<link rel="stylesheet" href="<?= base_url('plugins/daterangepicker/daterangepicker.css') ?>">
<script src="<?= base_url('plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('plugins/daterangepicker/daterangepicker.js') ?>"></script>
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
  $(function() {
    $('#tanggal_batas_cetak').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  });
</script>
<?= $this->endSection() ?>