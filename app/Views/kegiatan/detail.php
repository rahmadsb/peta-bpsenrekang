<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<style>
  .info-card {
    transition: transform 0.2s ease-in-out;
  }

  .info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .badge-status {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
  }
</style>

<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12">
      <a href="<?= base_url('kegiatan') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>

  <!-- Informasi Kegiatan -->
  <div class="card info-card mb-4">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-0">
        <i class="fas fa-calendar-check"></i> Detail Kegiatan
      </h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="150"><strong>Nama Kegiatan:</strong></td>
              <td><?= esc($opsi['nama_kegiatan'] ?? '-') ?></td>
            </tr>
            <tr>
              <td><strong>Kode Kegiatan:</strong></td>
              <td><?= esc($opsi['kode_kegiatan'] ?? '-') ?></td>
            </tr>
            <tr>
              <td><strong>Tahun:</strong></td>
              <td><?= esc($kegiatan['tahun']) ?></td>
            </tr>
            <tr>
              <td><strong>Bulan:</strong></td>
              <td><?= esc($kegiatan['bulan']) ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="150"><strong>Tanggal Batas Cetak:</strong></td>
              <td><?= esc($kegiatan['tanggal_batas_cetak']) ?></td>
            </tr>
            <tr>
              <td><strong>Status:</strong></td>
              <td>
                <span class="badge badge-info badge-status"><?= esc($kegiatan['status']) ?></span>
              </td>
            </tr>
            <tr>
              <td><strong>Dibuat:</strong></td>
              <td><?= date('d M Y H:i', strtotime($kegiatan['created_at'])) ?></td>
            </tr>
            <tr>
              <td><strong>Diupdate:</strong></td>
              <td><?= date('d M Y H:i', strtotime($kegiatan['updated_at'])) ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Daftar Wilkerstat -->
  <div class="row">
    <!-- Blok Sensus -->
    <div class="col-md-4 mb-4">
      <div class="card info-card h-100">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">
            <i class="fas fa-th-large"></i> Blok Sensus (<?= count($blokSensus) ?>)
          </h5>
        </div>
        <div class="card-body">
          <?php if (empty($blokSensus)): ?>
            <p class="text-muted">Tidak ada blok sensus yang terdaftar.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama BS</th>
                    <th>Nama SLS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($blokSensus as $blok): ?>
                    <tr>
                      <td><?= esc($blok['kode_bs']) ?></td>
                      <td><?= esc($blok['nama_bs']) ?></td>
                      <td><?= esc($blok['nama_sls']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- SLS -->
    <div class="col-md-4 mb-4">
      <div class="card info-card h-100">
        <div class="card-header bg-warning text-white">
          <h5 class="mb-0">
            <i class="fas fa-map-marked-alt"></i> SLS (<?= count($sls) ?>)
          </h5>
        </div>
        <div class="card-body">
          <?php if (empty($sls)): ?>
            <p class="text-muted">Tidak ada SLS yang terdaftar.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($sls as $slsItem): ?>
                    <tr>
                      <td><?= esc($slsItem['kode_sls']) ?></td>
                      <td><?= esc($slsItem['nama_sls']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Desa -->
    <div class="col-md-4 mb-4">
      <div class="card info-card h-100">
        <div class="card-header bg-danger text-white">
          <h5 class="mb-0">
            <i class="fas fa-map-marker-alt"></i> Desa (<?= count($desa) ?>)
          </h5>
        </div>
        <div class="card-body">
          <?php if (empty($desa)): ?>
            <p class="text-muted">Tidak ada desa yang terdaftar.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($desa as $desaItem): ?>
                    <tr>
                      <td><?= esc($desaItem['kode_desa']) ?></td>
                      <td><?= esc($desaItem['nama_desa']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
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
<?= $this->endSection() ?>