<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<style>
  /* Simplified styling */
  .peta-section {
    padding: 12px;
    margin-bottom: 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background: #fff;
  }

  .peta-section-header {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
  }

  .peta-utama-item {
    padding: 8px;
    margin-bottom: 6px;
    background: #f8f9fa;
    border-left: 3px solid #007bff;
    border-radius: 3px;
    font-size: 0.9rem;
  }

  .peta-inset-item {
    padding: 6px 8px;
    margin: 4px 0;
    background: #f0f8f4;
    border-left: 2px solid #28a745;
    border-radius: 3px;
    font-size: 0.85rem;
  }

  .file-actions {
    display: inline-flex;
    gap: 4px;
  }

  .btn-file-action {
    padding: 2px 6px;
    font-size: 0.75rem;
    border: 1px solid #dee2e6;
    background: white;
  }

  .btn-file-action:hover {
    background: #f8f9fa;
  }

  .upload-form-inline {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-top: 8px;
  }

  .upload-form-inline .form-control-sm {
    flex: 1;
    max-width: 200px;
  }

  .file-info-compact {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 4px;
  }

  .no-peta-placeholder {
    color: #adb5bd;
    font-style: italic;
    font-size: 0.85rem;
    padding: 8px;
  }

  .inset-count {
    display: inline-block;
    background: #e7f3ff;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.75rem;
    margin-left: 6px;
  }

  /* Compact table cell */
  .peta-cell {
    padding: 8px !important;
    font-size: 0.85rem;
  }

  /* Toggle buttons for jenis peta */
  .jenis-peta-toggle {
    display: flex;
    gap: 4px;
    margin-bottom: 8px;
  }

  .jenis-peta-btn {
    flex: 1;
    padding: 4px 8px;
    font-size: 0.75rem;
    border: 1px solid #dee2e6;
    background: #f8f9fa;
    cursor: pointer;
    text-align: center;
    border-radius: 3px;
  }

  .jenis-peta-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
  }

  .jenis-peta-content {
    display: none;
  }

  .jenis-peta-content.active {
    display: block;
  }

  /* Compact file item */
  .file-item-compact {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 8px;
    margin: 4px 0;
    background: #f8f9fa;
    border-radius: 3px;
    border-left: 3px solid #007bff;
  }

  .file-item-compact.inset {
    background: #f0f8f4;
    border-left-color: #28a745;
    padding: 4px 8px;
    font-size: 0.8rem;
  }

  .file-name {
    flex: 1;
    font-weight: 500;
    color: #212529;
  }

  .file-meta {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: 2px;
  }

  .file-actions-compact {
    display: flex;
    gap: 2px;
    margin-left: 8px;
  }

  .btn-icon {
    padding: 2px 6px;
    font-size: 0.7rem;
    border: none;
    background: transparent;
    color: #6c757d;
    cursor: pointer;
  }

  .btn-icon:hover {
    background: #e9ecef;
    border-radius: 2px;
  }

  /* Upload form compact */
  .upload-form-compact {
    display: flex;
    gap: 6px;
    align-items: center;
    margin-top: 6px;
  }

  .upload-form-compact input[type="file"] {
    flex: 1;
    font-size: 0.75rem;
    padding: 2px 4px;
  }

  .upload-form-compact button {
    padding: 2px 8px;
    font-size: 0.75rem;
  }

  /* Collapse inset */
  .inset-toggle {
    cursor: pointer;
    user-select: none;
    font-size: 0.75rem;
    color: #28a745;
    margin-top: 6px;
    display: inline-block;
  }

  .inset-toggle:hover {
    text-decoration: underline;
  }

  .inset-list {
    margin-top: 6px;
    max-height: 200px;
    overflow-y: auto;
  }

  /* Status badge */
  .status-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 500;
  }

  .status-badge.has-file {
    background: #d4edda;
    color: #155724;
  }

  .status-badge.no-file {
    background: #fff3cd;
    color: #856404;
  }
</style>
<div class="container-fluid">
  <h1><?= $title ?></h1>
  <h4>Kegiatan: <?= esc($kegiatan['nama_kegiatan'] ?? $kegiatan['id']) ?></h4>
  <hr>

  <!-- Batch Upload Section -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">📦 Batch Upload Peta (ZIP)</h5>
    </div>
    <div class="card-body">
      <form action="<?= base_url('kelola-peta-wilkerstat/batch-upload') ?>" method="post" enctype="multipart/form-data" id="batchUploadForm">
        <?= csrf_field() ?>
        <input type="hidden" name="id_kegiatan" value="<?= esc($kegiatan['id']) ?>">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="zip_file">Pilih File ZIP</label>
              <input type="file" name="zip_file" id="zip_file" accept=".zip" class="form-control" required>
              <small class="form-text text-muted">
                Format: File ZIP berisi file peta dengan nama sesuai kode SLS/BS/Desa<br>
                Peta Utama: <code>[kode].jpg</code> (contoh: 7316020009009B.jpg)<br>
                Peta Inset: <code>[kode]_[nomor].jpg</code> (contoh: 7316020009009B_1.jpg)
              </small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="jenis_peta_batch">Jenis Peta</label>
              <select name="jenis_peta" id="jenis_peta_batch" class="form-control" required>
                <option value="">Pilih Jenis Peta</option>
                <option value="dengan_titik">Peta dengan Titik Bangunan</option>
                <option value="tanpa_titik">Peta tanpa Titik Bangunan</option>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-upload"></i> Upload ZIP
        </button>
      </form>
    </div>
  </div>

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
                <th style="width:45%">Peta</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($wilkerstat[str_replace('-', '_', $type)] as $ws): ?>
                <tr>
                  <td><?= esc($ws['kode_' . ($type == 'blok-sensus' ? 'bs' : ($type == 'sls' ? 'sls' : 'desa'))]) ?></td>
                  <td><?= esc($ws['nama_' . ($type == 'blok-sensus' ? 'sls' : ($type == 'sls' ? 'sls' : 'desa'))]) ?></td>
                  <td class="peta-cell">
                    <!-- Toggle buttons untuk jenis peta -->
                    <div class="jenis-peta-toggle">
                      <div class="jenis-peta-btn active" data-jenis="dengan_titik">Dengan Titik</div>
                      <div class="jenis-peta-btn" data-jenis="tanpa_titik">Tanpa Titik</div>
                    </div>

                    <?php foreach ([['dengan_titik', 'Dengan Titik'], ['tanpa_titik', 'Tanpa Titik']] as $index => [$jenis, $labelPeta]): ?>
                      <?php
                      $petaUtama = $petaModel->where([
                        'id_kegiatan' => $kegiatan['id'],
                        'wilkerstat_type' => str_replace('-', '_', $type),
                        'id_wilkerstat' => $ws['id'],
                        'jenis_peta' => $jenis,
                        'id_parent_peta' => null
                      ])->findAll();
                      ?>
                      <div class="jenis-peta-content <?= $index === 0 ? 'active' : '' ?>" data-jenis="<?= $jenis ?>">
                        <?php if (empty($petaUtama)): ?>
                          <div class="no-peta-placeholder">
                            <span class="status-badge no-file">Belum ada peta</span>
                            <form action="<?= base_url('kelola-peta-wilkerstat/upload') ?>" method="post" enctype="multipart/form-data" class="upload-form-compact">
                              <?= csrf_field() ?>
                              <input type="hidden" name="id_kegiatan" value="<?= esc($kegiatan['id']) ?>">
                              <input type="hidden" name="wilkerstat_type" value="<?= str_replace('-', '_', $type) ?>">
                              <input type="hidden" name="id_wilkerstat" value="<?= esc($ws['id']) ?>">
                              <input type="hidden" name="jenis_peta" value="<?= $jenis ?>">
                              <input type="file" name="peta_files[]" accept=".jpg,.jpeg,.png" required class="form-control form-control-sm">
                              <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                            </form>
                          </div>
                        <?php else: ?>
                          <?php foreach ($petaUtama as $utama): ?>
                            <?php
                            $inset = $petaModel->where(['id_parent_peta' => $utama['id']])->findAll();
                            ?>
                            <div class="file-item-compact">
                              <div style="flex: 1;">
                                <div class="file-name"><?= esc($utama['nama_file']) ?></div>
                                <div class="file-meta">
                                  <?= date('d/m/Y', strtotime($utama['uploaded_at'])) ?>
                                  <?php if (!empty($inset)): ?>
                                    <span class="inset-count"><?= count($inset) ?> inset</span>
                                  <?php endif; ?>
                                </div>
                              </div>
                              <div class="file-actions-compact">
                                <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $utama['id']) ?>" class="btn-icon" target="_blank" title="Download">📥</a>
                                <?php if (in_array(strtolower(pathinfo($utama['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                                  <a href="<?= base_url('preview-peta/' . $utama['file_path']) ?>" class="btn-icon" target="_blank" title="Preview">👁️</a>
                                <?php endif; ?>
                                <button type="button" class="btn-icon btn-replace-file" title="Ganti">🔄</button>
                                <button type="button" class="btn-icon btn-rename-file" title="Rename">✏️</button>
                                <form action="<?= base_url('kelola-peta-wilkerstat/delete/' . $utama['id']) ?>" method="post" class="d-inline delete-form">
                                  <?= csrf_field() ?>
                                  <button type="button" class="btn-icon btn-delete" title="Hapus">🗑️</button>
                                </form>
                              </div>
                            </div>

                            <!-- Form rename (tersembunyi) -->
                            <form action="<?= base_url('kelola-peta-wilkerstat/rename/' . $utama['id']) ?>" method="post" class="form-rename-file d-none mt-2">
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
                            <form action="<?= base_url('kelola-peta-wilkerstat/replace/' . $utama['id']) ?>" method="post" enctype="multipart/form-data" class="form-replace-file d-none mt-2">
                              <?= csrf_field() ?>
                              <div class="input-group input-group-sm">
                                <input type="file" name="replace_file" accept=".jpg,.jpeg,.png" required class="form-control">
                                <div class="input-group-append">
                                  <button type="submit" class="btn btn-primary btn-sm">📤</button>
                                  <button type="button" class="btn btn-secondary btn-sm btn-cancel-replace">❌</button>
                                </div>
                              </div>
                            </form>

                            <!-- Upload inset & daftar inset -->
                            <?php if (!empty($inset)): ?>
                              <span class="inset-toggle" data-toggle="collapse" data-target="#inset-<?= $utama['id'] ?>" data-count="<?= count($inset) ?>">
                                ▼ Tampilkan <?= count($inset) ?> inset
                              </span>
                              <div id="inset-<?= $utama['id'] ?>" class="collapse inset-list">
                                <?php foreach ($inset as $file): ?>
                                  <div class="file-item-compact inset">
                                    <div style="flex: 1;">
                                      <div class="file-name"><?= esc($file['nama_file']) ?></div>
                                      <div class="file-meta"><?= date('d/m/Y', strtotime($file['uploaded_at'])) ?></div>
                                    </div>
                                    <div class="file-actions-compact">
                                      <a href="<?= base_url('kelola-peta-wilkerstat/download/' . $file['id']) ?>" class="btn-icon" target="_blank" title="Download">📥</a>
                                      <?php if (in_array(strtolower(pathinfo($file['nama_file'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])): ?>
                                        <a href="<?= base_url('preview-peta/' . $file['file_path']) ?>" class="btn-icon" target="_blank" title="Preview">👁️</a>
                                      <?php endif; ?>
                                      <button type="button" class="btn-icon btn-replace-file" title="Ganti">🔄</button>
                                      <button type="button" class="btn-icon btn-rename-file" title="Rename">✏️</button>
                                      <form action="<?= base_url('kelola-peta-wilkerstat/delete/' . $file['id']) ?>" method="post" class="d-inline delete-form">
                                        <?= csrf_field() ?>
                                        <button type="button" class="btn-icon btn-delete" title="Hapus">🗑️</button>
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
                                <?php endforeach; ?>
                              </div>
                            <?php endif; ?>

                            <!-- Form upload inset -->
                            <form action="<?= base_url('kelola-peta-wilkerstat/upload') ?>" method="post" enctype="multipart/form-data" class="upload-form-compact">
                              <?= csrf_field() ?>
                              <input type="hidden" name="id_kegiatan" value="<?= esc($kegiatan['id']) ?>">
                              <input type="hidden" name="wilkerstat_type" value="<?= str_replace('-', '_', $type) ?>">
                              <input type="hidden" name="id_wilkerstat" value="<?= esc($ws['id']) ?>">
                              <input type="hidden" name="jenis_peta" value="<?= $jenis ?>">
                              <input type="hidden" name="id_parent_peta" value="<?= $utama['id'] ?>">
                              <input type="file" name="peta_files[]" accept=".jpg,.jpeg,.png" multiple required class="form-control form-control-sm">
                              <button type="submit" class="btn btn-success btn-sm">+ Inset</button>
                            </form>
                          <?php endforeach; ?>
                        <?php endif; ?>
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
  // Show flash messages with SweetAlert2
  $(document).ready(function() {
    <?php if (session()->getFlashdata('success')): ?>
      const successMsg = <?= json_encode(session()->getFlashdata('success'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        html: successMsg,
        timer: 8000,
        showConfirmButton: true,
        confirmButtonText: 'OK',
        width: '700px',
        allowOutsideClick: false,
        allowEscapeKey: true
      });
    <?php elseif (session()->getFlashdata('error')): ?>
      const errorMsg = <?= json_encode(session()->getFlashdata('error'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
      Swal.fire({
        icon: 'error',
        title: 'Error Batch Upload',
        html: errorMsg,
        timer: null,
        showConfirmButton: true,
        confirmButtonText: 'OK',
        width: '800px',
        allowOutsideClick: false,
        allowEscapeKey: true
      });
    <?php endif; ?>
  });

  $(function() {
    // Toggle jenis peta
    $('.jenis-peta-btn').on('click', function() {
      const jenis = $(this).data('jenis');
      const cell = $(this).closest('.peta-cell');

      // Update button state
      cell.find('.jenis-peta-btn').removeClass('active');
      $(this).addClass('active');

      // Update content visibility
      cell.find('.jenis-peta-content').removeClass('active');
      cell.find('.jenis-peta-content[data-jenis="' + jenis + '"]').addClass('active');
    });

    // Inset collapse toggle
    $(document).on('click', '.inset-toggle', function(e) {
      e.preventDefault();
      const targetId = $(this).data('target');
      const $targetEl = $(targetId);
      const count = $(this).data('count') || 0;
      const $toggle = $(this);

      $targetEl.on('shown.bs.collapse', function() {
        $toggle.text('▲ Sembunyikan inset');
      });

      $targetEl.on('hidden.bs.collapse', function() {
        $toggle.text('▼ Tampilkan ' + count + ' inset');
      });

      $targetEl.collapse('toggle');
    });

    $('.btn-delete').on('click', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      const fileName = $(this).closest('.file-item-compact').find('.file-name').text() || 'file peta';
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
  // Preview nama file yang dipilih pada input file
  $(document).on('change', '.input-preview-files', function() {
    var files = this.files;
    var preview = $(this).closest('form').find('.preview-files');
    var isMultiple = $(this).attr('multiple') !== undefined;

    if (files.length > 0) {
      if (isMultiple) {
        // Multiple files (untuk inset)
        var list = '<ul style="margin-bottom:0;padding-left:18px">';
        for (var i = 0; i < files.length; i++) {
          list += '<li>' + files[i].name + '</li>';
        }
        list += '</ul>';
        preview.html(list);
      } else {
        // Single file (untuk peta utama)
        preview.html('<span class="text-info">📄 ' + files[0].name + '</span>');
      }
    } else {
      preview.html('');
    }
  });
  $(document).on('click', '.btn-replace-file', function() {
    var form = $(this).closest('.file-item-compact').nextAll('.form-replace-file').first();
    $('.form-replace-file').not(form).addClass('d-none');
    $('.form-rename-file').addClass('d-none'); // Hide all rename forms
    form.toggleClass('d-none');
  });
  $(document).on('click', '.btn-cancel-replace', function(e) {
    e.preventDefault();
    $(this).closest('.form-replace-file').addClass('d-none');
  });
  $(document).on('click', '.btn-rename-file', function() {
    var form = $(this).closest('.file-item-compact').nextAll('.form-rename-file').first();
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

  // Batch upload form validation
  $('#batchUploadForm').on('submit', function(e) {
    const zipFile = $('#zip_file')[0].files[0];
    const jenisPeta = $('#jenis_peta_batch').val();

    if (!zipFile) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Pilih file ZIP terlebih dahulu!'
      });
      return false;
    }

    if (!jenisPeta) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Pilih jenis peta terlebih dahulu!'
      });
      return false;
    }

    // Show loading
    Swal.fire({
      title: 'Memproses Upload...',
      html: 'Mohon tunggu, sedang mengekstrak dan memproses file ZIP.<br>Proses ini mungkin memakan waktu beberapa saat.',
      allowOutsideClick: false,
      showConfirmButton: false,
      willOpen: () => {
        Swal.showLoading();
      }
    });
  });
</script>
<?php $this->endSection(); ?>