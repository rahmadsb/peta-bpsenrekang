<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->getFlashdata('success')): ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= session()->getFlashdata('success') ?>',
        timer: 2500,
        showConfirmButton: false
      });
    <?php elseif (session()->getFlashdata('error')): ?>
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '<?= session()->getFlashdata('error') ?>',
        timer: 3000,
        showConfirmButton: false
      });
    <?php endif; ?>
  });
</script>
<div class="container-fluid">
  <h1><?= $title ?></h1>
  <h4>Kegiatan: <?= esc($kegiatan['nama_kegiatan'] ?? $kegiatan['uuid']) ?></h4>
  <hr>

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
    <?php foreach ([['blok-sensus', 'Blok Sensus'], ['sls', 'SLS'], ['desa', 'Desa']] as [$type, $label]): ?>
      <div class="tab-pane fade<?= $type == 'blok-sensus' ? ' show active' : '' ?>" id="<?= $type ?>" role="tabpanel">
        <?php if (!empty($wilkerstat[str_replace('-', '_', $type)])): ?>
          <table class="table table-bordered table-sm" id="table-<?= $type ?>">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th style="width:60%">Peta Utama & Inset</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($wilkerstat[str_replace('-', '_', $type)] as $ws): ?>
                <tr>
                  <td><?= esc($ws['kode_' . ($type == 'blok-sensus' ? 'bs' : ($type == 'sls' ? 'sls' : 'desa'))]) ?></td>
                  <td><?= esc($ws['nama_' . ($type == 'blok-sensus' ? 'sls' : ($type == 'sls' ? 'sls' : 'desa'))]) ?></td>
                  <td>
                    <?php foreach ([['dengan_titik', 'Peta dengan Titik Bangunan'], ['tanpa_titik', 'Peta tanpa Titik Bangunan']] as [$jenis, $labelPeta]): ?>
                      <div class="mb-2">
                        <span class="badge badge-secondary mr-1">Peta Utama: <?= $labelPeta ?></span>
                        <!-- Upload peta utama -->
                        <form action="<?= base_url('kelola-peta-wilkerstat/upload') ?>" method="post" enctype="multipart/form-data" class="d-inline">
                          <input type="hidden" name="kegiatan_uuid" value="<?= esc($kegiatan['uuid']) ?>">
                          <input type="hidden" name="wilkerstat_type" value="<?= str_replace('-', '_', $type) ?>">
                          <input type="hidden" name="wilkerstat_uuid" value="<?= esc($ws['uuid']) ?>">
                          <input type="hidden" name="jenis_peta" value="<?= $jenis ?>">
                          <input type="file" name="peta_files[]" accept=".jpg,.jpeg,.png" multiple required style="width:160px;display:inline-block;" class="input-preview-files">
                          <div class="preview-files mt-1 mb-1" style="font-size:90%;color:#555;"></div>
                          <button type="submit" class="btn btn-sm btn-primary ml-2">Upload Peta Utama</button>
                        </form>
                        <ul class="list-group list-group-flush mt-1">
                          <?php
                          $petaUtama = $petaModel->where([
                            'kegiatan_uuid' => $kegiatan['uuid'],
                            'wilkerstat_type' => str_replace('-', '_', $type),
                            'wilkerstat_uuid' => $ws['uuid'],
                            'jenis_peta' => $jenis,
                            'parent_peta_id' => null
                          ])->findAll();
                          ?>
                          <?php if (empty($petaUtama)): ?>
                            <li class="list-group-item py-1 px-2 text-muted">Belum ada peta utama.</li>
                          <?php else: ?>
                            <?php foreach ($petaUtama as $utama): ?>
                              <li class="list-group-item py-1 px-2">
                                <span class="font-weight-bold"><?= esc($utama['nama_file']) ?></span>
                                <small class="d-block text-muted" style="font-size:90%">Diunggah: <?= date('d-m-Y H:i', strtotime($utama['uploaded_at'])) ?> oleh <?= esc($utama['uploader']) ?>, <?= isset($utama['file_path']) && file_exists(WRITEPATH . 'uploads/' . $utama['file_path']) ? number_format(filesize(WRITEPATH . 'uploads/' . $utama['file_path']) / 1024, 1) . ' KB' : '-' ?></small>
                                <div class="d-flex align-items-center" style="gap:8px;">
                                  <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $utama['id']) ?>" class="btn btn-success btn-sm ml-2" target="_blank">Download</a>
                                  <?php if (in_array(strtolower(pathinfo($utama['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                                    <a href="<?= base_url('preview-peta/' . $utama['file_path']) ?>" class="btn btn-info btn-sm ml-1" target="_blank">Preview</a>
                                  <?php endif; ?>
                                  <button type="button" class="btn btn-warning btn-sm ml-1 btn-replace-file">Ganti</button>
                                  <button type="button" class="btn btn-secondary btn-sm ml-1 btn-rename-file">Rename</button>
                                  <form action="<?= base_url('kelola-peta-wilkerstat/rename/' . $utama['id']) ?>" method="post" class="form-rename-file d-none mt-2" style="max-width:300px;">
                                    <div class="input-group input-group-sm mb-1">
                                      <input type="text" name="new_nama_file" class="form-control" value="<?= esc($utama['nama_file']) ?>" required>
                                      <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <button type="button" class="btn btn-secondary btn-cancel-rename">Batal</button>
                                      </div>
                                    </div>
                                  </form>
                                  <form action="<?= base_url('kelola-peta-wilkerstat/delete/' . $utama['id']) ?>" method="post" class="d-inline delete-form">
                                    <button type="button" class="btn btn-danger btn-sm btn-delete ml-1">Hapus</button>
                                  </form>
                                </div>
                                <form action="<?= base_url('kelola-peta-wilkerstat/replace/' . $utama['id']) ?>" method="post" enctype="multipart/form-data" class="form-replace-file d-none mt-2" style="max-width:300px;">
                                  <input type="file" name="replace_file" accept=".jpg,.jpeg,.png" required class="form-control form-control-sm mb-1">
                                  <button type="submit" class="btn btn-sm btn-primary">Upload Pengganti</button>
                                  <button type="button" class="btn btn-sm btn-secondary btn-cancel-replace">Batal</button>
                                </form>
                                <!-- Upload inset -->
                                <form action="<?= base_url('kelola-peta-wilkerstat/upload') ?>" method="post" enctype="multipart/form-data" class="d-inline ml-3">
                                  <input type="hidden" name="kegiatan_uuid" value="<?= esc($kegiatan['uuid']) ?>">
                                  <input type="hidden" name="wilkerstat_type" value="<?= str_replace('-', '_', $type) ?>">
                                  <input type="hidden" name="wilkerstat_uuid" value="<?= esc($ws['uuid']) ?>">
                                  <input type="hidden" name="jenis_peta" value="<?= $jenis ?>">
                                  <input type="hidden" name="parent_peta_id" value="<?= $utama['id'] ?>">
                                  <div class="d-flex align-items-center" style="gap:8px;">
                                    <input type="file" name="peta_files[]" accept=".jpg,.jpeg,.png" multiple required style="width:140px;display:inline-block;" class="input-preview-files">
                                    <div class="preview-files" style="font-size:90%;color:#555;"></div>
                                    <button type="submit" class="btn btn-sm btn-secondary ml-2">Upload Inset</button>
                                  </div>
                                </form>
                                <!-- Daftar inset -->
                                <ul class="list-group list-group-flush mt-1">
                                  <?php
                                  $inset = $petaModel->where([
                                    'parent_peta_id' => $utama['id']
                                  ])->findAll();
                                  ?>
                                  <?php if (empty($inset)): ?>
                                    <li class="list-group-item py-1 px-2 text-muted">Belum ada inset.</li>
                                  <?php else: ?>
                                    <?php foreach ($inset as $file): ?>
                                      <li class="list-group-item py-1 px-2 d-flex justify-content-between align-items-center">
                                        <span><?= esc($file['nama_file']) ?></span>
                                        <small class="d-block text-muted" style="font-size:90%">Diunggah: <?= date('d-m-Y H:i', strtotime($file['uploaded_at'])) ?> oleh <?= esc($file['uploader']) ?>, <?= isset($file['file_path']) && file_exists(WRITEPATH . 'uploads/' . $file['file_path']) ? number_format(filesize(WRITEPATH . 'uploads/' . $file['file_path']) / 1024, 1) . ' KB' : '-' ?></small>
                                        <span>
                                          <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $file['id']) ?>" class="btn btn-success btn-sm" target="_blank">Download</a>
                                          <?php if (in_array(strtolower(pathinfo($file['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                                            <a href="<?= base_url('preview-peta/' . $file['file_path']) ?>" class="btn btn-info btn-sm" target="_blank">Preview</a>
                                          <?php endif; ?>
                                          <button type="button" class="btn btn-warning btn-sm btn-replace-file">Ganti</button>
                                          <button type="button" class="btn btn-secondary btn-sm btn-rename-file">Rename</button>
                                          <form action="<?= base_url('kelola-peta-wilkerstat/rename/' . $file['id']) ?>" method="post" class="form-rename-file d-none mt-2" style="max-width:300px;">
                                            <div class="input-group input-group-sm mb-1">
                                              <input type="text" name="new_nama_file" class="form-control" value="<?= esc($file['nama_file']) ?>" required>
                                              <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-secondary btn-cancel-rename">Batal</button>
                                              </div>
                                            </div>
                                          </form>
                                          <form action="<?= base_url('kelola-peta-wilkerstat/replace/' . $file['id']) ?>" method="post" enctype="multipart/form-data" class="form-replace-file d-none mt-2" style="max-width:300px;">
                                            <input type="file" name="replace_file" accept=".jpg,.jpeg,.png" required class="form-control form-control-sm mb-1">
                                            <button type="submit" class="btn btn-sm btn-primary">Upload Pengganti</button>
                                            <button type="button" class="btn btn-sm btn-secondary btn-cancel-replace">Batal</button>
                                          </form>
                                          <form action="<?= base_url('kelola-peta-wilkerstat/delete/' . $file['id']) ?>" method="post" class="d-inline delete-form">
                                            <button type="button" class="btn btn-danger btn-sm btn-delete">Hapus</button>
                                          </form>
                                        </span>
                                      </li>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
                                </ul>
                              </li>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </ul>
                      </div>
                    <?php endforeach; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="alert alert-info">Tidak ada <?= $label ?> terkait kegiatan ini.</div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
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
  $(function() {
    $('#table-blok-sensus').DataTable({
      stateSave: true,
      order: [
        [0, 'asc']
      ],
      columnDefs: [{
        orderable: false,
        targets: [2]
      }]
    });
    $('#table-sls').DataTable({
      stateSave: true,
      order: [
        [0, 'asc']
      ],
      columnDefs: [{
        orderable: false,
        targets: [2]
      }]
    });
    $('#table-desa').DataTable({
      stateSave: true,
      order: [
        [0, 'asc']
      ],
      columnDefs: [{
        orderable: false,
        targets: [2]
      }]
    });
    // Fix DataTables in tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
      var target = $(e.target).attr("href");
      $(target).find('table.dataTable').DataTable().columns.adjust().draw();
    });
  });
  // Preview nama file yang dipilih pada input file (multi-upload)
  $(document).on('change', '.input-preview-files', function() {
    var files = this.files;
    var preview = $(this).closest('form').find('.preview-files');
    if (files.length > 0) {
      var list = '<ul style="margin-bottom:0;padding-left:18px">';
      for (var i = 0; i < files.length; i++) {
        list += '<li>' + files[i].name + '</li>';
      }
      list += '</ul>';
      preview.html(list);
    } else {
      preview.html('');
    }
  });
  $(document).on('click', '.btn-replace-file', function() {
    var form = $(this).closest('li').find('.form-replace-file');
    $('.form-replace-file').not(form).addClass('d-none');
    form.toggleClass('d-none');
  });
  $(document).on('click', '.btn-cancel-replace', function(e) {
    e.preventDefault();
    $(this).closest('.form-replace-file').addClass('d-none');
  });
  $(document).on('click', '.btn-rename-file', function() {
    var form = $(this).closest('li, .list-group-item').find('.form-rename-file');
    $('.form-rename-file').not(form).addClass('d-none');
    form.toggleClass('d-none');
  });
  $(document).on('click', '.btn-cancel-rename', function(e) {
    e.preventDefault();
    $(this).closest('.form-rename-file').addClass('d-none');
  });
</script>
<?php $this->endSection(); ?>