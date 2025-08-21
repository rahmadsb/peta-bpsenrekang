<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<style>
  /* Custom styling for action buttons */
  .btn-action {
    margin-right: 2px;
    transition: all 0.2s ease-in-out;
    white-space: nowrap;
  }

  .btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .btn-action i {
    font-size: 0.875rem;
  }

  /* Responsive table wrapper */
  .table-responsive-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1rem;
  }

  /* Table styling improvements */
  #table {
    min-width: 800px;
    /* Minimum width to prevent cramping */
    margin-bottom: 0;
  }

  #table th,
  #table td {
    white-space: nowrap;
    vertical-align: middle;
  }

  /* Specific column width adjustments */
  #table th:nth-child(1) {
    min-width: 200px;
  }

  /* Opsi Kegiatan */
  #table th:nth-child(2) {
    min-width: 80px;
  }

  /* Tahun */
  #table th:nth-child(3) {
    min-width: 100px;
  }

  /* Bulan */
  #table th:nth-child(4) {
    min-width: 140px;
  }

  /* Tanggal Batas Cetak */
  #table th:nth-child(5) {
    min-width: 120px;
  }

  /* Status */
  #table th:nth-child(6) {
    min-width: 120px;
  }

  /* Dibuat oleh */
  #table th:nth-child(7) {
    min-width: 160px;
  }

  /* Aksi */

  /* Action buttons container */
  .action-buttons {
    display: flex;
    gap: 2px;
    justify-content: flex-start;
    align-items: center;
  }

  /* Mobile specific adjustments */
  @media (max-width: 767.98px) {
    .container {
      padding-left: 10px;
      padding-right: 10px;
    }

    .table-responsive-wrapper {
      margin-left: -10px;
      margin-right: -10px;
      padding-left: 10px;
      padding-right: 10px;
    }

    #table th,
    #table td {
      font-size: 0.875rem;
      padding: 0.5rem;
    }

    .btn-action {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }
  }
</style>
<div class="container mt-4">
  <h2>Manajemen Kegiatan</h2>
  <?php if (in_array($currentRole, ['ADMIN', 'IPDS', 'SUBJECT_MATTER'])): ?>
    <a href="<?= base_url('kegiatan/create') ?>" class="btn btn-primary mb-3">
      <i class="fas fa-plus"></i> Tambah Kegiatan
    </a>
  <?php endif; ?>
  <div class="table-responsive-wrapper">
    <table class="table table-bordered" id="table">
      <thead>
        <tr>
          <th>Opsi Kegiatan</th>
          <th>Tahun</th>
          <th>Bulan</th>
          <th>Tanggal Batas Cetak</th>
          <th>Status</th>
          <th>Dibuat oleh</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($kegiatan as $item): ?>
          <tr>
            <td><?= esc($opsiMap[$item['id_opsi_kegiatan']] ?? '-') ?></td>
            <td><?= esc($item['tahun']) ?></td>
            <td><?= esc($item['bulan']) ?></td>
            <td><?= esc($item['tanggal_batas_cetak']) ?></td>
            <td><?= esc($item['status']) ?></td>
            <td><?= esc($userMap[$item['id_user']] ?? 'Unknown') ?></td>
            <td>
              <div class="action-buttons">
                <a href="<?= base_url('kegiatan/detail/' . $item['id']) ?>" class="btn btn-sm btn-info btn-action" title="Detail">
                  <i class="fas fa-eye"></i>
                </a>
                <?php
                // Cek apakah user bisa mengedit/hapus kegiatan ini
                $canManageThis = false;
                if (in_array($currentRole, ['ADMIN', 'IPDS'])) {
                  $canManageThis = true; // Admin dan IPDS bisa manage semua kegiatan
                } elseif ($currentRole === 'SUBJECT_MATTER' && $item['id_user'] === $currentUserId) {
                  $canManageThis = true; // Subject Matter hanya bisa manage kegiatan miliknya
                }
                ?>
                <?php if ($canManageThis): ?>
                  <a href="<?= base_url('kegiatan/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning btn-action" title="Edit">
                    <i class="fas fa-edit"></i>
                  </a>
                  <?php if (in_array($currentRole, ['ADMIN', 'IPDS'])): ?>
                    <a href="<?= base_url('kelola-peta-wilkerstat/' . $item['id']) ?>" class="btn btn-sm btn-success btn-action" title="Kelola Peta Wilkerstat">
                      <i class="fas fa-map"></i>
                    </a>
                  <?php endif; ?>
                  <a href="#" class="btn btn-sm btn-danger btn-delete btn-action" data-url="<?= base_url('kegiatan/delete/' . $item['id']) ?>" title="Hapus">
                    <i class="fas fa-trash"></i>
                  </a>
                <?php elseif ($currentRole === 'SUBJECT_MATTER' && $item['id_user'] !== $currentUserId): ?>
                  <small class="text-muted">Dibuat oleh: <?= esc($userMap[$item['id_user']] ?? 'Unknown') ?></small>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
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
<script>
  $(document).ready(function() {
    $('#table').DataTable();
    $('.btn-delete').on('click', function(e) {
      e.preventDefault();
      var url = $(this).data('url');
      Swal.fire({
        title: 'Yakin ingin menghapus kegiatan ini?',
        text: "Tindakan ini tidak bisa dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
</script>
<?php if (session()->getFlashdata('success')): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Sukses!',
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
      title: 'Gagal!',
      text: '<?= session()->getFlashdata('error') ?>',
      timer: 2500,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>
<?= $this->endSection() ?>