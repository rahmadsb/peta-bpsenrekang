<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid">
  <h1><?= $title ?></h1>
  <h4>Kegiatan: <?= esc($kegiatan['nama_kegiatan'] ?? $kegiatan['uuid']) ?></h4>
  <hr>
  <?php foreach (['blok_sensus', 'sls', 'desa'] as $type): ?>
    <h5><?= ucwords(str_replace('_', ' ', $type)) ?></h5>
    <?php if (empty($wilkerstat[$type])): ?>
      <div class="alert alert-info">Tidak ada <?= $type ?> terkait kegiatan ini.</div>
    <?php else: ?>
      <?php foreach ($wilkerstat[$type] as $ws): ?>
        <div class="card mb-3">
          <div class="card-header">
            <?= esc($ws['nama'] ?? $ws['kode'] ?? $ws['uuid']) ?>
          </div>
          <div class="card-body">
            <?php foreach ([['dengan_titik', 'Peta dengan Titik Bangunan'], ['tanpa_titik', 'Peta tanpa Titik Bangunan']] as [$jenis, $label]): ?>
              <h6><?= $label ?></h6>
              <form action="<?= base_url('kelola-peta-wilkerstat/upload') ?>" method="post" enctype="multipart/form-data" class="mb-2">
                <input type="hidden" name="kegiatan_uuid" value="<?= esc($kegiatan['uuid']) ?>">
                <input type="hidden" name="wilkerstat_type" value="<?= $type ?>">
                <input type="hidden" name="wilkerstat_uuid" value="<?= esc($ws['uuid']) ?>">
                <input type="hidden" name="jenis_peta" value="<?= $jenis ?>">
                <label>Upload file (JPG/PNG, multi):</label>
                <input type="file" name="peta_files[]" accept=".jpg,.jpeg,.png" multiple required>
                <label class="ml-2"><input type="checkbox" name="is_inset" value="1"> File inset/perbesaran</label>
                <button type="submit" class="btn btn-sm btn-primary ml-2">Upload</button>
              </form>
              <ul class="list-group mb-3">
                <?php
                $files = $petaModel->where([
                  'kegiatan_uuid' => $kegiatan['uuid'],
                  'wilkerstat_type' => $type,
                  'wilkerstat_uuid' => $ws['uuid'],
                  'jenis_peta' => $jenis
                ])->findAll();
                ?>
                <?php if (empty($files)): ?>
                  <li class="list-group-item text-muted">Belum ada file peta diunggah.</li>
                <?php else: ?>
                  <?php foreach ($files as $file): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <?= esc($file['nama_file']) ?>
                      <?php if ($file['is_inset']): ?>
                        <span class="badge badge-info ml-2">Inset</span>
                      <?php endif; ?>
                      <span>
                        <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $file['id']) ?>" class="btn btn-success btn-sm">Download</a>
                        <form action="<?= base_url('kelola-peta-wilkerstat/delete/' . $file['id']) ?>" method="post" class="d-inline delete-form">
                          <button type="button" class="btn btn-danger btn-sm btn-delete">Hapus</button>
                        </form>
                      </span>
                    </li>
                  <?php endforeach; ?>
                <?php endif; ?>
              </ul>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <hr>
  <?php endforeach; ?>
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
    $('.btn-delete').on('click', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      Swal.fire({
        title: 'Yakin hapus file peta?',
        text: 'File yang dihapus tidak dapat dikembalikan!',
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
<?php $this->endSection(); ?>