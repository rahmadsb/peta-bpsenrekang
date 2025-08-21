<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
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

  /* Custom styling untuk DataTables */
  .dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
    margin-bottom: 1rem;
  }

  .dataTables_wrapper .dataTables_length {
    float: left;
    margin-bottom: 1rem;
  }

  .dataTables_wrapper .dataTables_info {
    clear: both;
    float: left;
    padding-top: 0.755em;
  }

  .dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
    padding-top: 0.25em;
  }

  /* Tab content styling */
  .tab-content {
    border: 1px solid #ddd;
    border-top: none;
    padding: 1rem;
    background-color: #fff;
    border-radius: 0 0 0.25rem 0.25rem;
  }

  .nav-tabs .nav-link.active {
    background-color: #fff;
    border-color: #ddd #ddd #fff;
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
  <div class="card info-card">
    <div class="card-header bg-info text-white">
      <h5 class="mb-0">
        <i class="fas fa-map"></i> Daftar Wilkerstat Terlibat
      </h5>
    </div>
    <div class="card-body">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" id="wilkerstatTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="blok-sensus-tab" data-toggle="tab" href="#blok-sensus" role="tab">
            <i class="fas fa-th-large"></i> Blok Sensus (<?= count($blokSensus) ?>)
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="sls-tab" data-toggle="tab" href="#sls" role="tab">
            <i class="fas fa-map-marked-alt"></i> SLS (<?= count($sls) ?>)
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="desa-tab" data-toggle="tab" href="#desa" role="tab">
            <i class="fas fa-map-marker-alt"></i> Desa (<?= count($desa) ?>)
          </a>
        </li>
      </ul>

      <!-- Tab content -->
      <div class="tab-content mt-3" id="wilkerstatTabContent">
        <!-- Blok Sensus Tab -->
        <div class="tab-pane fade show active" id="blok-sensus" role="tabpanel">
          <?php if (empty($blokSensus)): ?>
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i> Tidak ada blok sensus yang terdaftar dalam kegiatan ini.
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-blok-sensus">
                <thead class="thead-light">
                  <tr>
                    <th>No</th>
                    <th>Kode Blok Sensus</th>
                    <th>Nama SLS</th>
                    <th>Nama Desa</th>
                    <th>Nama Kecamatan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($blokSensus as $blok): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= esc($blok['kode_bs']) ?></td>
                      <td><?= esc($blok['nama_sls']) ?></td>
                      <td><?= esc($blok['nama_desa']) ?></td>
                      <td><?= esc($blok['nama_kecamatan']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <!-- SLS Tab -->
        <div class="tab-pane fade" id="sls" role="tabpanel">
          <?php if (empty($sls)): ?>
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i> Tidak ada SLS yang terdaftar dalam kegiatan ini.
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-sls">
                <thead class="thead-light">
                  <tr>
                    <th>No</th>
                    <th>Kode SLS</th>
                    <th>Nama SLS</th>
                    <th>Nama Desa</th>
                    <th>Nama Kecamatan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($sls as $slsItem): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= esc($slsItem['kode_sls']) ?></td>
                      <td><?= esc($slsItem['nama_sls']) ?></td>
                      <td><?= esc($slsItem['nama_desa']) ?></td>
                      <td><?= esc($slsItem['nama_kecamatan']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <!-- Desa Tab -->
        <div class="tab-pane fade" id="desa" role="tabpanel">
          <?php if (empty($desa)): ?>
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i> Tidak ada desa yang terdaftar dalam kegiatan ini.
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-desa">
                <thead class="thead-light">
                  <tr>
                    <th>No</th>
                    <th>Kode Desa</th>
                    <th>Nama Desa</th>
                    <th>Nama Kecamatan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($desa as $desaItem): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= esc($desaItem['kode_desa']) ?></td>
                      <td><?= esc($desaItem['nama_desa']) ?></td>
                      <td><?= esc($desaItem['nama_kecamatan']) ?></td>
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

<script>
  $(document).ready(function() {
    // Inisialisasi DataTable untuk semua tabel wilkerstat
    function initDataTable(tableId) {
      if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
      }

      $(tableId).DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "paging": true,
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100],
        "language": {
          "search": "Cari:",
          "lengthMenu": "Tampilkan _MENU_ data per halaman",
          "zeroRecords": "Data tidak ditemukan",
          "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
          "infoFiltered": "(difilter dari _MAX_ total data)",
          "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
          }
        },
        "columnDefs": [{
            "orderable": false,
            "targets": 0
          } // Kolom nomor tidak bisa diurutkan
        ]
      });
    }

    // Inisialisasi DataTable saat halaman dimuat
    initDataTable('#table-blok-sensus');
    initDataTable('#table-sls');
    initDataTable('#table-desa');

    // Re-inisialisasi DataTable saat tab diklik
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
      var target = $(e.target).attr("href");
      if (target === '#blok-sensus') {
        initDataTable('#table-blok-sensus');
      } else if (target === '#sls') {
        initDataTable('#table-sls');
      } else if (target === '#desa') {
        initDataTable('#table-desa');
      }
    });
  });
</script>
<?= $this->endSection() ?>