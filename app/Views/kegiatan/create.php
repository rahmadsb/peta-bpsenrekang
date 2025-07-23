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
    <h4>Pilih Wilkerstat untuk Kegiatan Ini</h4>
    <ul class="nav nav-tabs" id="wilkerstatTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="blok-sensus-tab" data-toggle="tab" href="#blok-sensus" role="tab">Blok Sensus</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="sls-tab" data-toggle="tab" href="#sls" role="tab">SLS</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="desa-tab" data-toggle="tab" href="#desa" role="tab">Desa</a>
      </li>
    </ul>
    <div class="tab-content mt-3" id="wilkerstatTabContent">
      <div class="tab-pane fade show active" id="blok-sensus" role="tabpanel">
        <input type="text" class="form-control mb-2 search-bs" placeholder="Cari blok sensus...">
        <table class="table table-bordered table-sm" id="table-blok-sensus">
          <thead>
            <tr>
              <th></th>
              <th>Kode</th>
              <th>Nama</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($blokSensusList as $bs): ?>
              <tr>
                <td><input type="checkbox" name="blok_sensus[]" value="<?= $bs['uuid'] ?>" class="cb-bs"></td>
                <td><?= esc($bs['kode_bs']) ?></td>
                <td><?= esc($bs['nama_sls']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="sls" role="tabpanel">
        <input type="text" class="form-control mb-2 search-sls" placeholder="Cari SLS...">
        <table class="table table-bordered table-sm" id="table-sls">
          <thead>
            <tr>
              <th></th>
              <th>Kode</th>
              <th>Nama</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($slsList as $sls): ?>
              <tr>
                <td><input type="checkbox" name="sls[]" value="<?= $sls['uuid'] ?>" class="cb-sls"></td>
                <td><?= esc($sls['kode_sls']) ?></td>
                <td><?= esc($sls['nama_sls']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="desa" role="tabpanel">
        <input type="text" class="form-control mb-2 search-desa" placeholder="Cari desa...">
        <table class="table table-bordered table-sm" id="table-desa">
          <thead>
            <tr>
              <th></th>
              <th>Kode</th>
              <th>Nama</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($desaList as $desa): ?>
              <tr>
                <td><input type="checkbox" name="desa[]" value="<?= $desa['uuid'] ?>" class="cb-desa"></td>
                <td><?= esc($desa['kode_desa']) ?></td>
                <td><?= esc($desa['nama_desa']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
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
  $(document).ready(function() {
    $('#table-desa').DataTable();
  });
  $(document).ready(function() {
    $('#table-sls').DataTable();
  });
  $(document).ready(function() {
    $('#table-blok-sensus').DataTable();
  });
</script>
<script>
  // Search global
  $('.search-bs').on('keyup', function() {
    var val = $(this).val().toLowerCase();
    $('#table-blok-sensus tbody tr').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
    });
  });
  $('.search-sls').on('keyup', function() {
    var val = $(this).val().toLowerCase();
    $('#table-sls tbody tr').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
    });
  });
  $('.search-desa').on('keyup', function() {
    var val = $(this).val().toLowerCase();
    $('#table-desa tbody tr').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
    });
  });
  // Sort by selected
  function sortByChecked(tableId, cbClass) {
    var rows = $(tableId + ' tbody tr').get();
    rows.sort(function(a, b) {
      var ac = $(a).find(cbClass).prop('checked') ? 0 : 1;
      var bc = $(b).find(cbClass).prop('checked') ? 0 : 1;
      return ac - bc;
    });
    $.each(rows, function(idx, row) {
      $(tableId + ' tbody').append(row);
    });
  }
  $('.cb-bs').on('change', function() {
    sortByChecked('#table-blok-sensus', '.cb-bs');
  });
  $('.cb-sls').on('change', function() {
    sortByChecked('#table-sls', '.cb-sls');
  });
  $('.cb-desa').on('change', function() {
    sortByChecked('#table-desa', '.cb-desa');
  });
</script>
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