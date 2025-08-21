<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  /* Compact button styling */
  .btn-xs {
    padding: 0.2rem 0.4rem;
    font-size: 0.75rem;
    line-height: 1.2;
    border-radius: 0.2rem;
    min-width: 28px;
    text-align: center;
  }

  /* Peta utama styling */
  .border-left-primary {
    border-left: 4px solid #007bff !important;
    background-color: #f8f9ff;
  }

  /* File preview styling */
  .preview-files {
    max-height: 60px;
    overflow-y: auto;
    font-size: 0.8rem;
  }

  /* Badge styling */
  .badge-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
  }

  /* Inset container styling */
  .inset-container {
    background-color: #f0fff4;
    border: 1px solid #28a745;
    border-radius: 0.375rem;
  }

  /* Upload section styling */
  .upload-section {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 0.375rem 0.375rem;
  }

  /* Hover effects */
  .btn-xs:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
  }

  /* File info styling */
  .file-info {
    font-size: 0.75rem;
    color: #6c757d;
    line-height: 1.3;
  }

  /* Table responsive fix */
  .table-responsive-vertical {
    overflow-x: auto;
  }
</style>
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
  <h4>Kegiatan: <?= esc($kegiatan['nama_kegiatan'] ?? $kegiatan['id']) ?></h4>
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
                      <div class="mb-3 border rounded p-2" style="background-color: #f8f9fa;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <span class="badge badge-info badge-sm">📍 <?= $labelPeta ?></span>
                          <!-- Upload peta utama -->
                          <form action="<?= base_url('kelola-peta-wilkerstat/upload') ?>" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_kegiatan" value="<?= esc($kegiatan['id']) ?>">
                            <input type="hidden" name="wilkerstat_type" value="<?= str_replace('-', '_', $type) ?>">
                            <input type="hidden" name="id_wilkerstat" value="<?= esc($ws['id']) ?>">
                            <input type="hidden" name="jenis_peta" value="<?= $jenis ?>">
                            <input type="file" name="peta_files[]" accept=".jpg,.jpeg,.png" multiple required class="form-control form-control-sm mr-2 input-preview-files" style="max-width: 180px;">
                            <button type="submit" class="btn btn-primary btn-sm">📤 Upload</button>
                          </form>
                        </div>
                        <div class="preview-files mb-2" style="font-size:85%;color:#666;"></div>
                        <ul class="list-group list-group-flush">
                          <?php
                          $petaUtama = $petaModel->where([
                            'id_kegiatan' => $kegiatan['id'],
                            'wilkerstat_type' => str_replace('-', '_', $type),
                            'id_wilkerstat' => $ws['id'],
                            'jenis_peta' => $jenis,
                            'id_parent_peta' => null
                          ])->findAll();
                          ?>
                          <?php if (empty($petaUtama)): ?>
                            <li class="list-group-item py-2 px-3 text-muted bg-light">
                              📋 Belum ada peta utama
                            </li>
                          <?php else: ?>
                            <?php foreach ($petaUtama as $utama): ?>
                              <li class="list-group-item py-2 px-3 border-left-primary" style="border-left: 4px solid #007bff;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                  <div class="flex-grow-1">
                                    <span class="badge badge-primary badge-sm mr-2">PETA UTAMA</span>
                                    <span class="font-weight-bold text-dark"><?= esc($utama['nama_file']) ?></span>
                                    <small class="d-block text-muted mt-1" style="font-size:85%">
                                      📅 <?= date('d/m/Y H:i', strtotime($utama['uploaded_at'])) ?> • 👤 <?= esc($utama['uploader']) ?> • 📁 <?= isset($utama['file_path']) && file_exists(WRITEPATH . 'uploads/' . $utama['file_path']) ? number_format(filesize(WRITEPATH . 'uploads/' . $utama['file_path']) / 1024, 1) . ' KB' : '-' ?>
                                    </small>
                                  </div>
                                  <div class="btn-group-sm" role="group">
                                    <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $utama['id']) ?>" class="btn btn-outline-success btn-xs mr-1" target="_blank" title="Download">📥</a>
                                    <?php if (in_array(strtolower(pathinfo($utama['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                                      <a href="<?= base_url('preview-peta/' . $utama['file_path']) ?>" class="btn btn-outline-info btn-xs mr-1" target="_blank" title="Preview">👁️</a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-warning btn-xs mr-1 btn-replace-file" title="Ganti">🔄</button>
                                    <button type="button" class="btn btn-outline-secondary btn-xs mr-1 btn-rename-file" title="Rename">✏️</button>
                                    <form action="<?= base_url('kelola-peta-wilkerstat/delete/' . $utama['id']) ?>" method="post" class="d-inline delete-form">
                                      <?= csrf_field() ?>
                                      <button type="button" class="btn btn-outline-danger btn-xs btn-delete" title="Hapus">🗑️</button>
                                    </form>
                                  </div>
                                </div>

                                <!-- Form rename (tersembunyi) -->
                                <form action="<?= base_url('kelola-peta-wilkerstat/rename/' . $utama['id']) ?>" method="post" class="form-rename-file d-none mb-2">
                                  <?= csrf_field() ?>
                                  <div class="input-group input-group-sm">
                                    <input type="text" name="new_nama_file" class="form-control" value="<?= esc(pathinfo($utama['nama_file'], PATHINFO_FILENAME)) ?>" required>
                                    <div class="input-group-append">
                                      <span class="input-group-text">.<?= esc(pathinfo($utama['nama_file'], PATHINFO_EXTENSION)) ?></span>
                                      <button type="submit" class="btn btn-primary btn-sm">💾</button>
                                      <button type="button" class="btn btn-secondary btn-sm btn-cancel-rename">❌</button>
                                    </div>
                                  </div>
                                </form>

                                <!-- Form replace (tersembunyi) -->
                                <form action="<?= base_url('kelola-peta-wilkerstat/replace/' . $utama['id']) ?>" method="post" enctype="multipart/form-data" class="form-replace-file d-none mb-2">
                                  <?= csrf_field() ?>
                                  <div class="input-group input-group-sm">
                                    <input type="file" name="replace_file" accept=".jpg,.jpeg,.png" required class="form-control">
                                    <div class="input-group-append">
                                      <button type="submit" class="btn btn-primary btn-sm">📤</button>
                                      <button type="button" class="btn btn-secondary btn-sm btn-cancel-replace">❌</button>
                                    </div>
                                  </div>
                                </form> <!-- Upload inset section -->
                                <div class="border-top pt-2 mt-2" style="background-color: #f8f9fa;">
                                  <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-success badge-sm mr-2">UPLOAD INSET</span>
                                    <form action="<?= base_url('kelola-peta-wilkerstat/upload') ?>" method="post" enctype="multipart/form-data" class="d-flex align-items-center flex-grow-1">
                                      <?= csrf_field() ?>
                                      <input type="hidden" name="id_kegiatan" value="<?= esc($kegiatan['id']) ?>">
                                      <input type="hidden" name="wilkerstat_type" value="<?= str_replace('-', '_', $type) ?>">
                                      <input type="hidden" name="id_wilkerstat" value="<?= esc($ws['id']) ?>">
                                      <input type="hidden" name="jenis_peta" value="<?= $jenis ?>">
                                      <input type="hidden" name="id_parent_peta" value="<?= $utama['id'] ?>">
                                      <input type="file" name="peta_files[]" accept=".jpg,.jpeg,.png" multiple required class="form-control form-control-sm mr-2 input-preview-files" style="max-width: 200px;">
                                      <button type="submit" class="btn btn-success btn-sm">📤 Upload</button>
                                    </form>
                                  </div>
                                  <div class="preview-files ml-2" style="font-size:85%;color:#666;"></div>
                                </div>

                                <!-- Daftar inset -->
                                <div class="mt-2">
                                  <?php
                                  $inset = $petaModel->where([
                                    'id_parent_peta' => $utama['id']
                                  ])->findAll();
                                  ?>
                                  <?php if (!empty($inset)): ?>
                                    <div class="mb-1">
                                      <span class="badge badge-success badge-sm">📍 INSET FILES (<?= count($inset) ?>)</span>
                                    </div>
                                    <?php foreach ($inset as $file): ?>
                                      <div class="border border-success rounded p-2 mb-1" style="background-color: #f0fff4;">
                                        <div class="d-flex justify-content-between align-items-start">
                                          <div class="flex-grow-1">
                                            <span class="text-dark"><?= esc($file['nama_file']) ?></span>
                                            <small class="d-block text-muted" style="font-size:80%">
                                              📅 <?= date('d/m/Y H:i', strtotime($file['uploaded_at'])) ?> • 👤 <?= esc($file['uploader']) ?> • 📁 <?= isset($file['file_path']) && file_exists(WRITEPATH . 'uploads/' . $file['file_path']) ? number_format(filesize(WRITEPATH . 'uploads/' . $file['file_path']) / 1024, 1) . ' KB' : '-' ?>
                                            </small>
                                          </div>
                                          <div class="btn-group-sm" role="group">
                                            <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $file['id']) ?>" class="btn btn-outline-success btn-xs mr-1" target="_blank" title="Download">📥</a>
                                            <?php if (in_array(strtolower(pathinfo($file['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                                              <a href="<?= base_url('preview-peta/' . $file['file_path']) ?>" class="btn btn-outline-info btn-xs mr-1" target="_blank" title="Preview">👁️</a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-outline-warning btn-xs mr-1 btn-replace-file" title="Ganti">🔄</button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs mr-1 btn-rename-file" title="Rename">✏️</button>
                                            <form action="<?= base_url('kelola-peta-wilkerstat/delete/' . $file['id']) ?>" method="post" class="d-inline delete-form">
                                              <?= csrf_field() ?>
                                              <button type="button" class="btn btn-outline-danger btn-xs btn-delete" title="Hapus">🗑️</button>
                                            </form>
                                          </div>
                                        </div>

                                        <!-- Form rename inset (tersembunyi) -->
                                        <form action="<?= base_url('kelola-peta-wilkerstat/rename/' . $file['id']) ?>" method="post" class="form-rename-file d-none mt-2">
                                          <?= csrf_field() ?>
                                          <div class="input-group input-group-sm">
                                            <input type="text" name="new_nama_file" class="form-control" value="<?= esc(pathinfo($file['nama_file'], PATHINFO_FILENAME)) ?>" required>
                                            <div class="input-group-append">
                                              <span class="input-group-text">.<?= esc(pathinfo($file['nama_file'], PATHINFO_EXTENSION)) ?></span>
                                              <button type="submit" class="btn btn-primary btn-sm">💾</button>
                                              <button type="button" class="btn btn-secondary btn-sm btn-cancel-rename">❌</button>
                                            </div>
                                          </div>
                                        </form>

                                        <!-- Form replace inset (tersembunyi) -->
                                        <form action="<?= base_url('kelola-peta-wilkerstat/replace/' . $file['id']) ?>" method="post" enctype="multipart/form-data" class="form-replace-file d-none mt-2">
                                          <?= csrf_field() ?>
                                          <div class="input-group input-group-sm">
                                            <input type="file" name="replace_file" accept=".jpg,.jpeg,.png" required class="form-control">
                                            <div class="input-group-append">
                                              <button type="submit" class="btn btn-primary btn-sm">📤</button>
                                              <button type="button" class="btn btn-secondary btn-sm btn-cancel-replace">❌</button>
                                            </div>
                                          </div>
                                        </form>
                                      </div>
                                    <?php endforeach; ?>
                                  <?php else: ?>
                                    <div class="text-muted text-center py-2" style="font-size:85%;">
                                      📍 Belum ada inset untuk peta ini
                                    </div>
                                  <?php endif; ?>
                                </div>
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
      const fileName = $(this).closest('li, .border').find('.font-weight-bold, .text-dark').first().text();
      Swal.fire({
        title: 'Yakin hapus file peta?',
        html: `File: <strong>${fileName}</strong><br/>File yang dihapus tidak dapat dikembalikan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Show loading
          Swal.fire({
            title: 'Menghapus file...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
              Swal.showLoading();
            }
          });
          form.submit();
        }
      });
    });

    // Validation for rename form
    $(document).on('submit', '.form-rename-file', function(e) {
      const namaFile = $(this).find('input[name="new_nama_file"]').val().trim();
      if (namaFile === '') {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Nama file tidak boleh kosong!'
        });
        return false;
      }
      if (!/^[a-zA-Z0-9\s\-_\(\)]+$/.test(namaFile)) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Nama file hanya boleh mengandung huruf, angka, spasi, tanda minus, underscore, dan tanda kurung!'
        });
        return false;
      }
    });

    // Validation for replace form
    $(document).on('submit', '.form-replace-file', function(e) {
      const fileInput = $(this).find('input[type="file"]')[0];
      if (!fileInput.files.length) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Pilih file untuk mengganti!'
        });
        return false;
      }

      const file = fileInput.files[0];
      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'File harus berformat JPG atau PNG!'
        });
        return false;
      }
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
    var form = $(this).closest('li, .border').find('.form-replace-file');
    $('.form-replace-file').not(form).addClass('d-none');
    $('.form-rename-file').addClass('d-none'); // Hide all rename forms
    form.toggleClass('d-none');
  });
  $(document).on('click', '.btn-cancel-replace', function(e) {
    e.preventDefault();
    $(this).closest('.form-replace-file').addClass('d-none');
  });
  $(document).on('click', '.btn-rename-file', function() {
    var form = $(this).closest('li, .border').find('.form-rename-file');
    $('.form-rename-file').not(form).addClass('d-none');
    $('.form-replace-file').addClass('d-none'); // Hide all replace forms
    form.toggleClass('d-none');
    // Focus on input field when opened
    if (!form.hasClass('d-none')) {
      setTimeout(function() {
        form.find('input[name="new_nama_file"]').focus().select();
      }, 100);
    }
  });
  $(document).on('click', '.btn-cancel-rename', function(e) {
    e.preventDefault();
    $(this).closest('.form-rename-file').addClass('d-none');
  });
</script>
<?php $this->endSection(); ?>