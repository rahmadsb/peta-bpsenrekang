<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
  <h2>Edit Kegiatan</h2>
  <form action="<?= base_url('kegiatan/update/' . $kegiatan['id']) ?>" method="post">
    <div class="mb-3">
      <label for="id_opsi_kegiatan" class="form-label">Opsi Kegiatan</label>
      <select class="form-control<?= isset($validation) && $validation->hasError('id_opsi_kegiatan') ? ' is-invalid' : '' ?>" id="id_opsi_kegiatan" name="id_opsi_kegiatan" required>
        <option value="">Pilih Opsi Kegiatan</option>
        <?php foreach ($opsi as $o): ?>
          <option value="<?= $o['id'] ?>" <?= old('id_opsi_kegiatan', $kegiatan['id_opsi_kegiatan']) == $o['id'] ? 'selected' : '' ?>><?= esc($o['nama_kegiatan']) ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (isset($validation) && $validation->hasError('id_opsi_kegiatan')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('id_opsi_kegiatan') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="tahun" class="form-label">Tahun</label>
      <input type="number" class="form-control<?= isset($validation) && $validation->hasError('tahun') ? ' is-invalid' : '' ?>" id="tahun" name="tahun" value="<?= old('tahun', $kegiatan['tahun']) ?>" required>
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
          <option value="<?= $bulan ?>" <?= old('bulan', $kegiatan['bulan']) == $bulan ? 'selected' : '' ?>><?= $bulan ?></option>
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
      <input type="text" class="form-control<?= isset($validation) && $validation->hasError('tanggal_batas_cetak') ? ' is-invalid' : '' ?>" id="tanggal_batas_cetak" name="tanggal_batas_cetak" value="<?= old('tanggal_batas_cetak', $kegiatan['tanggal_batas_cetak']) ?>" required autocomplete="off">
      <?php if (isset($validation) && $validation->hasError('tanggal_batas_cetak')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('tanggal_batas_cetak') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <?php if (isset($canChangeStatus) && $canChangeStatus): ?>
        <select class="form-control<?= isset($validation) && $validation->hasError('status') ? ' is-invalid' : '' ?>" id="status" name="status" required>
          <?php foreach ($statusList as $status): ?>
            <option value="<?= $status ?>" <?= old('status', $kegiatan['status']) == $status ? 'selected' : '' ?>><?= $status ?></option>
          <?php endforeach; ?>
        </select>
      <?php else: ?>
        <input type="text" class="form-control" value="<?= esc($kegiatan['status']) ?>" readonly>
        <input type="hidden" name="status" value="<?= esc($kegiatan['status']) ?>">
        <small class="form-text text-muted">Anda tidak memiliki akses untuk mengubah status kegiatan.</small>
      <?php endif; ?>
      <?php if (isset($validation) && $validation->hasError('status')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('status') ?>
        </div>
      <?php endif; ?>
    </div>
    <!-- Tombol download template dan upload file import wilkerstat -->
    <div class="mb-3">
      <a href="<?= base_url('template_import_wilkerstat.xlsx') ?>" class="btn btn-success btn-sm" download>
        <i class="fas fa-download mr-1"></i> Download Template Import
      </a>
      <button type="button" class="btn btn-info btn-sm btn-import-wilkerstat ml-2">
        <i class="fas fa-file-import mr-1"></i> Import Wilkerstat dari Excel
      </button>
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
        <button type="button" class="btn btn-sm btn-primary mb-2 btn-select-all" data-table="table-blok-sensus">Pilih Semua</button>
        <button type="button" class="btn btn-sm btn-secondary mb-2 btn-unselect-all" data-table="table-blok-sensus">Uncheck Semua</button>
        <button type="button" class="btn btn-sm btn-success mb-2 ml-1 btn-select-all-tab" data-table="table-blok-sensus">Pilih Semua (semua data)</button>
        <button type="button" class="btn btn-sm btn-danger mb-2 ml-1 btn-unselect-all-tab" data-table="table-blok-sensus">Uncheck Semua (semua data)</button>
        <small class="text-muted d-block mb-2">Aksi hanya berlaku pada data yang sedang tampil (hasil filter/pencarian aktif).</small>
        <span class="badge badge-info ml-2 count-selected" id="count-blok-sensus">0 terpilih</span>
        <table class="table table-bordered table-sm" id="table-blok-sensus">
          <thead>
            <tr>
              <th class="dt-checkbox"></th>
              <th>Kode Blok Sensus</th>
              <th>Nama SLS</th>
              <th>Nama Kecamatan</th>
              <th>Nama Desa</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($blokSensusList as $bs): ?>
              <tr>
                <td class="dt-checkbox"><input type="checkbox" name="blok_sensus[]" value="<?= $bs['id'] ?>" class="cb-bs" <?= in_array($bs['id'], $selectedBlokSensus ?? []) ? 'checked' : '' ?>></td>
                <td><?= esc($bs['kode_bs']) ?></td>
                <td><?= esc($bs['nama_sls']) ?></td>
                <td><?= esc($bs['nama_kecamatan']) ?></td>
                <td><?= esc($bs['nama_desa']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="sls" role="tabpanel">
        <button type="button" class="btn btn-sm btn-primary mb-2 btn-select-all" data-table="table-sls">Pilih Semua</button>
        <button type="button" class="btn btn-sm btn-secondary mb-2 btn-unselect-all" data-table="table-sls">Uncheck Semua</button>
        <button type="button" class="btn btn-sm btn-success mb-2 ml-1 btn-select-all-tab" data-table="table-sls">Pilih Semua (semua data)</button>
        <button type="button" class="btn btn-sm btn-danger mb-2 ml-1 btn-unselect-all-tab" data-table="table-sls">Uncheck Semua (semua data)</button>
        <small class="text-muted d-block mb-2">Aksi hanya berlaku pada data yang sedang tampil (hasil filter/pencarian aktif).</small>
        <span class="badge badge-info ml-2 count-selected" id="count-sls">0 terpilih</span>
        <table class="table table-bordered table-sm" id="table-sls">
          <thead>
            <tr>
              <th class="dt-checkbox"></th>
              <th>Kode SLS</th>
              <th>Nama SLS</th>
              <th>Nama Kecamatan</th>
              <th>Nama Desa</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($slsList as $sls): ?>
              <tr>
                <td class="dt-checkbox"><input type="checkbox" name="sls[]" value="<?= $sls['id'] ?>" class="cb-sls" <?= in_array($sls['id'], $selectedSls ?? []) ? 'checked' : '' ?>></td>
                <td><?= esc($sls['kode_sls']) ?></td>
                <td><?= esc($sls['nama_sls']) ?></td>
                <td><?= esc($sls['nama_kecamatan']) ?></td>
                <td><?= esc($sls['nama_desa']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="desa" role="tabpanel">
        <button type="button" class="btn btn-sm btn-primary mb-2 btn-select-all" data-table="table-desa">Pilih Semua</button>
        <button type="button" class="btn btn-sm btn-secondary mb-2 btn-unselect-all" data-table="table-desa">Uncheck Semua</button>
        <button type="button" class="btn btn-sm btn-success mb-2 ml-1 btn-select-all-tab" data-table="table-desa">Pilih Semua (semua data)</button>
        <button type="button" class="btn btn-sm btn-danger mb-2 ml-1 btn-unselect-all-tab" data-table="table-desa">Uncheck Semua (semua data)</button>
        <small class="text-muted d-block mb-2">Aksi hanya berlaku pada data yang sedang tampil (hasil filter/pencarian aktif).</small>
        <span class="badge badge-info ml-2 count-selected" id="count-desa">0 terpilih</span>
        <table class="table table-bordered table-sm" id="table-desa">
          <thead>
            <tr>
              <th class="dt-checkbox"></th>
              <th>Kode Desa</th>
              <th>Nama Desa</th>
              <th>Nama Kecamatan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($desaList as $desa): ?>
              <tr>
                <td class="dt-checkbox"><input type="checkbox" name="desa[]" value="<?= $desa['id'] ?>" class="cb-desa" <?= in_array($desa['id'], $selectedDesa ?? []) ? 'checked' : '' ?>></td>
                <td><?= esc($desa['kode_desa']) ?></td>
                <td><?= esc($desa['nama_desa']) ?></td>
                <td><?= esc($desa['nama_kecamatan']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('kegiatan') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>
<!-- Date Range Picker -->
<link rel="stylesheet" href="<?= base_url('plugins/daterangepicker/daterangepicker.css') ?>">
<script src="<?= base_url('plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('plugins/daterangepicker/daterangepicker.js') ?>"></script>
<!-- SheetJS Excel Library -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
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

<!-- Global Variables -->
<script>
  // Global variables for all scripts
  var appBaseUrl = '<?= base_url() ?>';
  var baseUrl = '<?= base_url() ?>';

  // Initialize selection arrays from PHP data with unique prefixes to avoid conflicts
  window.selectedBlokSensus = <?php echo json_encode(array_map(function ($id) {
                                return 'bs_' . $id;
                              }, $selectedBlokSensus ?? [])); ?>;
  window.selectedSls = <?php echo json_encode(array_map(function ($id) {
                          return 'sls_' . $id;
                        }, $selectedSls ?? [])); ?>;
  window.selectedDesa = <?php echo json_encode(array_map(function ($id) {
                          return 'desa_' . $id;
                        }, $selectedDesa ?? [])); ?>;
  window.selected_bs = [...window.selectedBlokSensus];
  window.selected_sls = [...window.selectedSls];
  window.selected_desa = [...window.selectedDesa];
</script>

<!-- Initialize DateRangePicker -->
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

<!-- Unified DataTable and Checkbox Management -->
<script>
  $(function() {
    // Configuration for each table
    var tableConfigs = [{
        tableId: '#table-blok-sensus',
        type: 'bs',
        counterId: '#count-blok-sensus',
        selectedArray: 'selected_bs',
        globalArray: 'selected_bs'
      },
      {
        tableId: '#table-sls',
        type: 'sls',
        counterId: '#count-sls',
        selectedArray: 'selected_sls',
        globalArray: 'selected_sls'
      },
      {
        tableId: '#table-desa',
        type: 'desa',
        counterId: '#count-desa',
        selectedArray: 'selected_desa',
        globalArray: 'selected_desa'
      }
    ];

    // Custom sorting function for checkbox column - uses global arrays instead of DOM
    $.fn.dataTable.ext.order['dom-checkbox'] = function(settings, col) {
      const api = this.api();
      let selectedArray = [];

      // Safely get table ID
      let tableId = '';
      try {
        if (settings && settings.sTable && settings.sTable.id) {
          tableId = '#' + settings.sTable.id;
        } else if (settings && settings.nTable) {
          tableId = '#' + settings.nTable.id;
        }
      } catch (e) {
        console.warn('Could not determine table ID for sorting:', e);
        return [];
      }

      // Determine which selection array to use based on table ID
      if (tableId === '#table-blok-sensus') {
        selectedArray = window.selected_bs || [];
      } else if (tableId === '#table-sls') {
        selectedArray = window.selected_sls || [];
      } else if (tableId === '#table-desa') {
        selectedArray = window.selected_desa || [];
      }

      return api.column(col, {
        order: 'index'
      }).data().map(function(cellData, index) {
        // Extract checkbox value from the cell data
        let checkboxValue = '';
        try {
          if (typeof cellData === 'string') {
            const match = cellData.match(/value="([^"]+)"/);
            checkboxValue = match ? match[1] : '';
          }
        } catch (e) {
          console.warn('Error extracting checkbox value:', e);
        }

        // Create prefixed value based on config
        let prefixedValue = '';
        if (selectedArray === window.selected_bs) {
          prefixedValue = 'bs_' + checkboxValue;
        } else if (selectedArray === window.selected_sls) {
          prefixedValue = 'sls_' + checkboxValue;
        } else if (selectedArray === window.selected_desa) {
          prefixedValue = 'desa_' + checkboxValue;
        }

        // Return '1' if this item is in the selected array, '0' otherwise
        return selectedArray.includes(prefixedValue) ? '1' : '0';
      });
    };

    // Initialize each DataTable with proper checkbox handling
    tableConfigs.forEach(function(config) {
      console.log('Initializing DataTable for:', config.tableId);

      // Destroy existing DataTable if it exists
      if ($.fn.DataTable.isDataTable(config.tableId)) {
        console.log('Destroying existing DataTable for:', config.tableId);
        $(config.tableId).DataTable().destroy();
      }

      var table = $(config.tableId).DataTable({
        destroy: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        lengthChange: true,
        autoWidth: false,
        columnDefs: [{
          targets: 0,
          orderable: true,
          orderDataType: 'dom-checkbox'
        }],
        drawCallback: function() {
          // Sync checkboxes after each redraw
          syncCheckboxes(config);
        }
      });

      // Handle checkbox changes
      $(config.tableId).on('change', 'input[type=checkbox]', function() {
        var value = $(this).val();
        var isChecked = $(this).prop('checked');

        // Add unique prefix to avoid conflicts between different wilkerstat types
        var prefixedValue = '';
        if (config.selectedArray === 'selected_bs') {
          prefixedValue = 'bs_' + value;
        } else if (config.selectedArray === 'selected_sls') {
          prefixedValue = 'sls_' + value;
        } else if (config.selectedArray === 'selected_desa') {
          prefixedValue = 'desa_' + value;
        }

        if (isChecked) {
          // Add to selection if not already present
          if (!window[config.selectedArray].includes(prefixedValue)) {
            window[config.selectedArray].push(prefixedValue);
          }
          if (!window[config.globalArray].includes(prefixedValue)) {
            window[config.globalArray].push(prefixedValue);
          }
          $(this).closest('tr').addClass('selected-row');
        } else {
          // Remove from selection
          window[config.selectedArray] = window[config.selectedArray].filter(v => v !== prefixedValue);
          window[config.globalArray] = window[config.globalArray].filter(v => v !== prefixedValue);
          $(this).closest('tr').removeClass('selected-row');
        }

        updateCounter(config);
        updateTabBadges();
      });

      // Select All button (current page only)
      $('.btn-select-all[data-table="' + config.tableId.substring(1) + '"]').on('click', function() {
        table.rows({
          search: 'applied',
          page: 'current'
        }).nodes().to$().find('input[type=checkbox]').each(function() {
          var value = $(this).val();
          $(this).prop('checked', true);
          $(this).closest('tr').addClass('selected-row');
          var prefixedValue = (config.selectedArray === 'selected_bs') ? ('bs_' + value) :
            (config.selectedArray === 'selected_sls') ? ('sls_' + value) :
            ('desa_' + value);
          if (!window[config.selectedArray].includes(prefixedValue)) {
            window[config.selectedArray].push(prefixedValue);
          }
          if (!window[config.globalArray].includes(prefixedValue)) {
            window[config.globalArray].push(prefixedValue);
          }
        });

        updateCounter(config);
        updateTabBadges();
      });

      // Unselect All button  (current page only)
      $('.btn-unselect-all[data-table="' + config.tableId.substring(1) + '"]').on('click', function() {
        table.rows({
          search: 'applied',
          page: 'current'
        }).nodes().to$().find('input[type=checkbox]').each(function() {
          var value = $(this).val();
          $(this).prop('checked', false);
          $(this).closest('tr').removeClass('selected-row');
          var prefixedValue = (config.selectedArray === 'selected_bs') ? ('bs_' + value) :
            (config.selectedArray === 'selected_sls') ? ('sls_' + value) :
            ('desa_' + value);
          window[config.selectedArray] = window[config.selectedArray].filter(v => v !== prefixedValue);
          window[config.globalArray] = window[config.globalArray].filter(v => v !== prefixedValue);
        });

        updateCounter(config);
        updateTabBadges();
      });

      // Select All (all data in tab)
      $('.btn-select-all-tab[data-table="' + config.tableId.substring(1) + '"]').on('click', function() {
        table.rows({
          search: 'applied'
        }).every(function() {
          var $cb = $(this.node()).find('input[type=checkbox]');
          var rawValue = $cb.val();
          if (!rawValue) return;

          var prefixedValue = (config.selectedArray === 'selected_bs') ? ('bs_' + rawValue) :
            (config.selectedArray === 'selected_sls') ? ('sls_' + rawValue) :
            ('desa_' + rawValue);

          if (!window[config.selectedArray].includes(prefixedValue)) {
            window[config.selectedArray].push(prefixedValue);
          }
          if (!window[config.globalArray].includes(prefixedValue)) {
            window[config.globalArray].push(prefixedValue);
          }
        });
        syncCheckboxes(config);
        updateCounter(config);
        updateTabBadges();
      });

      // Unselect All (all data in tab)
      $('.btn-unselect-all-tab[data-table="' + config.tableId.substring(1) + '"]').on('click', function() {
        table.rows({
          search: 'applied'
        }).every(function() {
          var $cb = $(this.node()).find('input[type=checkbox]');
          var rawValue = $cb.val();
          if (!rawValue) return;

          var prefixedValue = (config.selectedArray === 'selected_bs') ? ('bs_' + rawValue) :
            (config.selectedArray === 'selected_sls') ? ('sls_' + rawValue) :
            ('desa_' + rawValue);

          window[config.selectedArray] = window[config.selectedArray].filter(function(v) {
            return v !== prefixedValue;
          });
          window[config.globalArray] = window[config.globalArray].filter(function(v) {
            return v !== prefixedValue;
          });
        });
        syncCheckboxes(config);
        updateCounter(config);
        updateTabBadges();
      });

      // Initialize checkboxes and counter
      syncCheckboxes(config);
      updateCounter(config);
    });

    // Function to sync checkbox states
    function syncCheckboxes(config) {
      $(config.tableId + ' tbody tr').each(function() {
        var checkbox = $(this).find('input[type=checkbox]');
        var value = checkbox.val();
        var prefixedValue = (config.selectedArray === 'selected_bs') ? ('bs_' + value) :
          (config.selectedArray === 'selected_sls') ? ('sls_' + value) :
          ('desa_' + value);
        var shouldBeChecked = window[config.selectedArray].includes(prefixedValue);

        checkbox.prop('checked', shouldBeChecked);
        if (shouldBeChecked) {
          $(this).addClass('selected-row');
        } else {
          $(this).removeClass('selected-row');
        }
      });
    }

    // Function to update counter
    function updateCounter(config) {
      $(config.counterId).text(window[config.selectedArray].length + ' terpilih');
    }

    // Function to update tab badges
    function updateTabBadges() {
      console.log('Array Status:', {
        selected_bs: window.selected_bs ? window.selected_bs.length : 'undefined',
        selected_sls: window.selected_sls ? window.selected_sls.length : 'undefined',
        selected_desa: window.selected_desa ? window.selected_desa.length : 'undefined',
        selected_bs_sample: window.selected_bs ? window.selected_bs.slice(0, 3) : 'undefined',
        selected_sls_sample: window.selected_sls ? window.selected_sls.slice(0, 3) : 'undefined',
        selected_desa_sample: window.selected_desa ? window.selected_desa.slice(0, 3) : 'undefined'
      });

      // Blok Sensus tab
      if (window.selected_bs && window.selected_bs.length > 0) {
        let badge = $('#blok-sensus-tab .badge');
        if (badge.length === 0) {
          $('#blok-sensus-tab').append(`<span class="badge badge-pill badge-info ml-1">(${window.selected_bs.length} terpilih)</span>`);
        } else {
          badge.text(`(${window.selected_bs.length} terpilih)`);
        }
      } else {
        $('#blok-sensus-tab .badge').remove();
      }

      // SLS tab
      if (window.selected_sls.length > 0) {
        let badge = $('#sls-tab .badge');
        if (badge.length === 0) {
          $('#sls-tab').append(`<span class="badge badge-pill badge-info ml-1">(${window.selected_sls.length} terpilih)</span>`);
        } else {
          badge.text(`(${window.selected_sls.length} terpilih)`);
        }
      } else {
        $('#sls-tab .badge').remove();
      }

      // Desa tab
      if (window.selected_desa.length > 0) {
        let badge = $('#desa-tab .badge');
        if (badge.length === 0) {
          $('#desa-tab').append(`<span class="badge badge-pill badge-info ml-1">(${window.selected_desa.length} terpilih)</span>`);
        } else {
          badge.text(`(${window.selected_desa.length} terpilih)`);
        }
      } else {
        $('#desa-tab .badge').remove();
      }
    }

    // Form submission - add hidden inputs
    $('form').on('submit', function(e) {
      try {
        console.log('FORM SUBMIT: Preparing data...', {
          selected_bs_count: window.selected_bs ? window.selected_bs.length : 0,
          selected_sls_count: window.selected_sls ? window.selected_sls.length : 0,
          selected_desa_count: window.selected_desa ? window.selected_desa.length : 0,
          selected_bs_sample: window.selected_bs ? window.selected_bs.slice(0, 5) : [],
          selected_sls_sample: window.selected_sls ? window.selected_sls.slice(0, 5) : [],
          selected_desa_sample: window.selected_desa ? window.selected_desa.slice(0, 5) : []
        });

        // Remove existing hidden inputs
        $('input[name="blok_sensus[]"][type=hidden]').remove();
        $('input[name="sls[]"][type=hidden]').remove();
        $('input[name="desa[]"][type=hidden]').remove();

        // Add hidden inputs for selected items (remove prefix before submitting)
        if (window.selected_bs) {
          window.selected_bs.forEach(function(val) {
            // Remove 'bs_' prefix for database storage
            const actualValue = val.startsWith('bs_') ? val.substring(3) : val;
            $('<input>').attr({
              type: 'hidden',
              name: 'blok_sensus[]',
              value: actualValue
            }).appendTo('form');
          });
        }

        if (window.selected_sls) {
          window.selected_sls.forEach(function(val) {
            // Remove 'sls_' prefix for database storage
            const actualValue = val.startsWith('sls_') ? val.substring(4) : val;
            $('<input>').attr({
              type: 'hidden',
              name: 'sls[]',
              value: actualValue
            }).appendTo('form');
          });
        }

        if (window.selected_desa) {
          window.selected_desa.forEach(function(val) {
            // Remove 'desa_' prefix for database storage
            const actualValue = val.startsWith('desa_') ? val.substring(5) : val;
            $('<input>').attr({
              type: 'hidden',
              name: 'desa[]',
              value: actualValue
            }).appendTo('form');
          });
        }
      } catch (error) {
        console.error('Error preparing form submission:', error);
        e.preventDefault();
      }
    });

    // Global function for manual sync (used by import functions)
    window.forceSyncCheckboxes = function() {
      tableConfigs.forEach(function(config) {
        syncCheckboxes(config);
        updateCounter(config);

        // Force DataTable to refresh its sorting data after sync
        const table = $(config.tableId).DataTable();
        table.columns.adjust().draw(false);
      });
      updateTabBadges();
    };

    // Enhanced function to refresh sorting after import
    window.refreshTableSorting = function() {
      try {
        tableConfigs.forEach(function(config) {
          if ($.fn.DataTable.isDataTable(config.tableId)) {
            const table = $(config.tableId).DataTable();
            // Invalidate the sorting cache and redraw
            table.rows().invalidate('data').draw(false);

            // Force page length to be respected
            if (table.page.len() !== 10) {
              console.log('Fixing page length for', config.tableId, 'from', table.page.len(), 'to 10');
              table.page.len(10).draw();
            }
          }
        });
      } catch (e) {
        console.warn('Error in refreshTableSorting:', e);
      }
    };

    // Force page length function with pagination fix
    window.forcePageLength = function(length = 10) {
      try {
        tableConfigs.forEach(function(config) {
          if ($.fn.DataTable.isDataTable(config.tableId)) {
            const table = $(config.tableId).DataTable();
            console.log('Setting page length for', config.tableId, 'to', length);

            // Set page length and go to first page to fix numbering
            table.page.len(length);
            table.page(0).draw(false);

            // Force pagination to recalculate
            setTimeout(function() {
              table.draw(false);
            }, 50);
          }
        });
      } catch (e) {
        console.warn('Error in forcePageLength:', e);
      }
    };

    // Initialize tab badges
    updateTabBadges();

    // Force pagination after a short delay to ensure all elements are ready
    setTimeout(function() {
      try {
        console.log('Force applying page length after delay...');
        if (window.forcePageLength && typeof window.forcePageLength === 'function') {
          window.forcePageLength(10);
        }
      } catch (e) {
        console.warn('Error in delayed forcePageLength:', e);
      }
    }, 1000);
  });
</script>

<!-- CSS for selected rows -->
<style>
  .selected-row {
    background-color: rgba(0, 123, 255, 0.1) !important;
  }

  .selected-row:hover {
    background-color: rgba(0, 123, 255, 0.15) !important;
  }

  /* Style for sortable checkbox column */
  table.dataTable thead .sorting,
  table.dataTable thead .sorting_asc,
  table.dataTable thead .sorting_desc {
    cursor: pointer;
  }

  /* Add sorting icons for checkbox column */
  table.dataTable thead th:first-child {
    position: relative;
  }

  table.dataTable thead th:first-child:after {
    content: "\f0dc";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.5;
  }

  table.dataTable thead th:first-child.sorting_asc:after {
    content: "\f0de";
    opacity: 1;
  }

  table.dataTable thead th:first-child.sorting_desc:after {
    content: "\f0dd";
    opacity: 1;
  }
</style>

<!-- BS Custom File Input -->
<script src="<?= base_url('plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script>
<!-- Import Wilkerstat Script -->
<script src="<?= base_url('js/sls-checkbox-fixer.js') ?>"></script>
<script>
  // Global variables for base URL
  var baseUrl = '<?= base_url() ?>';
  var appBaseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('js/client-side-excel-import.js') ?>"></script>

<?php include(__DIR__ . '/../import_wilkerstat/modal.php'); ?>
<?= $this->endSection() ?>