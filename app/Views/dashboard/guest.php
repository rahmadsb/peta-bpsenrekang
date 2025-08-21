<?= $this->extend('index') ?>

<?= $this->section('content') ?>
<style>
  /* Chart container styling */
  #statusChart {
    max-height: 400px;
    width: 100% !important;
  }

  /* Dashboard card improvements */
  .dashboard-card {
    transition: transform 0.2s ease-in-out;
    min-height: 120px;
  }

  .dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  /* Responsive card body */
  .dashboard-card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 1rem;
  }

  .dashboard-card .card-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    text-align: center;
    line-height: 1.2;
  }

  .dashboard-card h2 {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0;
    line-height: 1;
  }

  /* Responsive adjustments */
  @media (max-width: 575.98px) {
    .dashboard-card .card-title {
      font-size: 0.8rem;
    }

    .dashboard-card h2 {
      font-size: 1.5rem;
    }

    .dashboard-card {
      min-height: 100px;
    }

    .dashboard-card .card-body {
      padding: 0.75rem;
    }
  }

  @media (min-width: 576px) and (max-width: 767.98px) {
    .dashboard-card .card-title {
      font-size: 0.85rem;
    }

    .dashboard-card h2 {
      font-size: 1.6rem;
    }
  }

  @media (min-width: 768px) and (max-width: 991.98px) {
    .dashboard-card .card-title {
      font-size: 0.9rem;
    }

    .dashboard-card h2 {
      font-size: 1.7rem;
    }
  }

  @media (min-width: 992px) {
    .dashboard-card .card-title {
      font-size: 1rem;
    }

    .dashboard-card h2 {
      font-size: 2rem;
    }
  }
</style>
<div class="container-fluid">
  <h1>Dashboard Guest</h1>
  <div class="row mt-4">
    <div class="col-6 col-sm-6 col-md-3 mb-3">
      <div class="card text-white bg-primary h-100 dashboard-card">
        <div class="card-body text-center">
          <h5 class="card-title">Total Kegiatan</h5>
          <h2><?= $totalKegiatan ?></h2>
        </div>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-md-3 mb-3">
      <div class="card text-white bg-success h-100 dashboard-card">
        <div class="card-body text-center">
          <h5 class="card-title">Total Peta</h5>
          <h2><?= $totalPeta ?></h2>
        </div>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-md-3 mb-3">
      <div class="card text-white bg-warning h-100 dashboard-card">
        <div class="card-body text-center">
          <h5 class="card-title">SLS</h5>
          <h2><?= $totalSls ?></h2>
        </div>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-md-3 mb-3">
      <div class="card text-white bg-dark h-100 dashboard-card">
        <div class="card-body text-center">
          <h5 class="card-title">Desa</h5>
          <h2><?= $totalDesa ?></h2>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Status Kegiatan</h5>
        </div>
        <div class="card-body">
          <canvas id="statusChart"></canvas>
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
  const ctx = document.getElementById('statusChart').getContext('2d');
  const statusChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($statusList) ?>,
      datasets: [{
        label: 'Jumlah Kegiatan',
        data: <?= json_encode(array_values($kegiatanPerStatus)) ?>,
        backgroundColor: [
          '#007bff', '#28a745', '#ffc107', '#17a2b8', '#343a40'
        ],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<?= $this->endSection() ?>