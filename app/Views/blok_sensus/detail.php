<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<style>
  /* Custom styling for action buttons */
  .btn-action {
    margin-right: 4px;
    transition: all 0.2s ease-in-out;
    min-width: 32px;
    padding: 0.25rem 0.4rem;
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
  <div class="mb-3">
    <strong>Kode BS:</strong> <?= esc($blok['kode_bs']) ?><br>
    <strong>Nama SLS:</strong> <?= esc($blok['nama_sls']) ?><br>
    <strong>Nama Desa:</strong> <?= esc($blok['nama_desa']) ?><br>
    <strong>Kecamatan:</strong> <?= esc($blok['nama_kecamatan']) ?><br>
    <strong>Kabupaten:</strong> <?= esc($blok['nama_kabupaten']) ?><br>
    <strong>Provinsi:</strong> <?= esc($blok['nama_provinsi']) ?><br>
  </div>
  <h4>Daftar Kegiatan yang Melibatkan Blok Sensus Ini</h4>
  <table class="table table-bordered table-sm" id="table-kegiatan">
    <thead>
      <tr>
        <th>Nama Kegiatan</th>
        <th>Tahun</th>
        <th>Bulan</th>
        <th>Status</th>
        <th>Peta (Download/Preview)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($daftarKegiatan as $k): ?>
        <tr>
          <td><?= esc($k['nama_kegiatan']) ?></td>
          <td><?= esc($k['tahun']) ?></td>
          <td><?= esc($k['bulan']) ?></td>
          <td><?= esc($k['status']) ?></td>
          <td>
            <?php foreach ([['dengan_titik', 'Peta dengan Titik Bangunan'], ['tanpa_titik', 'Peta tanpa Titik Bangunan']] as [$jenis, $label]): ?>
              <div class="mb-3 border rounded p-2" style="background-color: #f8f9fa;">
                <div class="mb-2"><span class="badge badge-secondary mr-1"><?= $label ?></span></div>

                <?php
                $allFiles = array_filter($k['peta'], function ($p) use ($jenis) {
                  return $p['jenis_peta'] === $jenis;
                });

                // Pisahkan peta utama dan inset
                $mainFiles = array_filter($allFiles, function ($p) {
                  return $p['id_parent_peta'] == null;
                });

                $insetFiles = array_filter($allFiles, function ($p) {
                  return $p['id_parent_peta'] != null;
                });
                ?>

                <!-- Peta Utama -->
                <div class="mb-2">
                  <strong class="text-primary">Peta Utama:</strong>
                  <?php if (empty($mainFiles)): ?>
                    <span class="text-muted ml-2">Belum ada file</span>
                  <?php else: ?>
                    <div class="ml-3 mt-1">
                      <?php foreach ($mainFiles as $file): ?>
                        <div class="mb-1">
                          <span class="text-dark font-weight-bold"><?= esc($file['nama_file']) ?></span>
                          <div class="mt-1">
                            <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $file['id']) ?>" class="btn btn-success btn-sm btn-action" target="_blank" title="Download">
                              <i class="fas fa-download"></i>
                            </a>
                            <?php if (in_array(strtolower(pathinfo($file['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                              <a href="<?= base_url('preview-peta/' . $file['file_path']) ?>" class="btn btn-info btn-sm btn-action" target="_blank" title="Preview">
                                <i class="fas fa-eye"></i>
                              </a>
                            <?php endif; ?>
                            <small class="text-muted ml-2">
                              (<?= date('d/m/Y H:i', strtotime($file['uploaded_at'])) ?>)
                            </small>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Peta Inset -->
                <div class="mb-1">
                  <strong class="text-success">Peta Inset:</strong>
                  <?php if (empty($insetFiles)): ?>
                    <span class="text-muted ml-2">Belum ada inset</span>
                  <?php else: ?>
                    <div class="ml-3 mt-1">
                      <?php foreach ($insetFiles as $file): ?>
                        <div class="mb-1 p-2 border-left border-success" style="background-color: #f0fff4;">
                          <span class="text-dark"><?= esc($file['nama_file']) ?></span>
                          <span class="badge badge-success badge-sm ml-1">INSET</span>
                          <div class="mt-1">
                            <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $file['id']) ?>" class="btn btn-outline-success btn-sm btn-action" target="_blank" title="Download">
                              <i class="fas fa-download"></i>
                            </a>
                            <?php if (in_array(strtolower(pathinfo($file['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                              <a href="<?= base_url('preview-peta/' . $file['file_path']) ?>" class="btn btn-outline-info btn-sm btn-action" target="_blank" title="Preview">
                                <i class="fas fa-eye"></i>
                              </a>
                            <?php endif; ?>
                            <small class="text-muted ml-2">
                              (<?= date('d/m/Y H:i', strtotime($file['uploaded_at'])) ?>)
                            </small>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
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
<script src="<?= base_url('plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>
  $(function() {
    $('#table-kegiatan').DataTable();
  });
</script>
<?php $this->endSection(); ?>