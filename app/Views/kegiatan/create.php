<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
  <h2>Tambah Kegiatan</h2>
  <form action="<?= base_url('kegiatan/store') ?>" method="post">
    <div class="mb-3">
      <label for="id_opsi_kegiatan" class="form-label">Opsi Kegiatan</label>
      <select class="form-control<?= isset($validation) && $validation->hasError('id_opsi_kegiatan') ? ' is-invalid' : '' ?>" id="id_opsi_kegiatan" name="id_opsi_kegiatan" required>
        <option value="">Pilih Opsi Kegiatan</option>
        <?php foreach ($opsi as $o): ?>
          <option value="<?= $o['id'] ?>" <?= old('id_opsi_kegiatan') == $o['id'] ? 'selected' : '' ?>><?= esc($o['nama_kegiatan']) ?></option>
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
      <input type="number" class="form-control<?= isset($validation) && $validation->hasError('tahun') ? ' is-invalid' : '' ?>" id="tahun" name="tahun" value="<?= old('tahun', date('Y')) ?>" required>
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
          <option value="<?= $bulan ?>" <?= old('bulan', date('F')) == $bulan ? 'selected' : '' ?>><?= $bulan ?></option>
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
      <input type="text" class="form-control<?= isset($validation) && $validation->hasError('tanggal_batas_cetak') ? ' is-invalid' : '' ?>" id="tanggal_batas_cetak" name="tanggal_batas_cetak" value="<?= old('tanggal_batas_cetak') ?>" required autocomplete="off">
      <?php if (isset($validation) && $validation->hasError('tanggal_batas_cetak')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('tanggal_batas_cetak') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label class="form-label">Status</label>
      <input type="text" class="form-control" value="disiapkan (IPDS)" readonly>
    </div>
    <!-- Tombol download template dan upload file import wilkerstat -->
    <div class="mb-3">
      <a href="<?= base_url('contoh_import_wilkerstat.xlsx') ?>" class="btn btn-success btn-sm" download>Download Template Import Wilkerstat</a>
      <div class="d-inline-block ms-2">
        <label for="file_import_wilkerstat" class="btn btn-info btn-sm mb-0">Import Wilkerstat dari Excel</label>
        <input type="file" name="file_import_wilkerstat" id="file_import_wilkerstat" accept=".xlsx,.xls" style="display:none;">
      </div>
      <div id="import-wilkerstat-error" class="text-danger mt-2" style="display:none;"></div>
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
        <small class="text-muted d-block mb-2">Aksi hanya berlaku pada data yang sedang tampil (hasil filter/pencarian aktif).</small>
        <span class="badge badge-info ml-2 count-selected" id="count-blok-sensus">0 terpilih</span>
        <table class="table table-bordered table-sm" id="table-blok-sensus">
          <thead>
            <tr>
              <th class="dt-checkbox"></th>
              <th>Kode Blok Sensus</th>
              <th>Nama Kecamatan</th>
              <th>Nama Desa</th>
              <th>Nama SLS</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($blokSensusList as $bs): ?>
              <tr>
                <td class="dt-checkbox"><input type="checkbox" name="blok_sensus[]" value="<?= $bs['id'] ?>" class="cb-bs"></td>
                <td><?= esc($bs['kode_bs']) ?></td>
                <td><?= esc($bs['nama_kecamatan']) ?></td>
                <td><?= esc($bs['nama_desa']) ?></td>
                <td><?= esc($bs['nama_sls']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="sls" role="tabpanel">
        <button type="button" class="btn btn-sm btn-primary mb-2 btn-select-all" data-table="table-sls">Pilih Semua</button>
        <button type="button" class="btn btn-sm btn-secondary mb-2 btn-unselect-all" data-table="table-sls">Uncheck Semua</button>
        <small class="text-muted d-block mb-2">Aksi hanya berlaku pada data yang sedang tampil (hasil filter/pencarian aktif).</small>
        <span class="badge badge-info ml-2 count-selected" id="count-sls">0 terpilih</span>
        <table class="table table-bordered table-sm" id="table-sls">
          <thead>
            <tr>
              <th class="dt-checkbox"></th>
              <th>Kode SLS</th>
              <th>Nama Kecamatan</th>
              <th>Nama Desa</th>
              <th>Nama SLS</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($slsList as $sls): ?>
              <tr>
                <td class="dt-checkbox"><input type="checkbox" name="sls[]" value="<?= $sls['id'] ?>" class="cb-sls"></td>
                <td><?= esc($sls['kode_sls']) ?></td>
                <td><?= esc($sls['nama_kecamatan']) ?></td>
                <td><?= esc($sls['nama_desa']) ?></td>
                <td><?= esc($sls['nama_sls']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="desa" role="tabpanel">
        <button type="button" class="btn btn-sm btn-primary mb-2 btn-select-all" data-table="table-desa">Pilih Semua</button>
        <button type="button" class="btn btn-sm btn-secondary mb-2 btn-unselect-all" data-table="table-desa">Uncheck Semua</button>
        <small class="text-muted d-block mb-2">Aksi hanya berlaku pada data yang sedang tampil (hasil filter/pencarian aktif).</small>
        <span class="badge badge-info ml-2 count-selected" id="count-desa">0 terpilih</span>
        <table class="table table-bordered table-sm" id="table-desa">
          <thead>
            <tr>
              <th class="dt-checkbox"></th>
              <th>Kode Desa</th>
              <th>Nama Kecamatan</th>
              <th>Nama Desa</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($desaList as $desa): ?>
              <tr>
                <td class="dt-checkbox"><input type="checkbox" name="desa[]" value="<?= $desa['id'] ?>" class="cb-desa"></td>
                <td><?= esc($desa['kode_desa']) ?></td>
                <td><?= esc($desa['nama_kecamatan']) ?></td>
                <td><?= esc($desa['nama_desa']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('kegiatan') ?>" class="btn btn-secondary">Batal</a>
  </form>
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
<script src="<?= base_url('plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('plugins/daterangepicker/daterangepicker.js') ?>"></script>
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
    $('#table-desa').DataTable();
  });
  $(document).ready(function() {
    $('#table-sls').DataTable();
  });
  $(document).ready(function() {
    $('#table-blok-sensus').DataTable();
  });
</script>
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
<script>
  $(function() {
    // Array manual untuk menyimpan pilihan user
    var selectedBlokSensus = [];
    var selectedSls = [];
    var selectedDesa = [];

    var tableIds = [{
        type: 'blok-sensus',
        arr: selectedBlokSensus,
        badge: '#count-blok-sensus'
      },
      {
        type: 'sls',
        arr: selectedSls,
        badge: '#count-sls'
      },
      {
        type: 'desa',
        arr: selectedDesa,
        badge: '#count-desa'
      }
    ];

    tableIds.forEach(function(obj) {
      var tableId = '#table-' + obj.type;
      var badgeId = obj.badge;
      // Gunakan array global by reference
      var getArr = function() {
        if (obj.type === 'blok-sensus') return selectedBlokSensus;
        if (obj.type === 'sls') return selectedSls;
        if (obj.type === 'desa') return selectedDesa;
      };
      var setArr = function(newArr) {
        if (obj.type === 'blok-sensus') selectedBlokSensus = newArr;
        if (obj.type === 'sls') selectedSls = newArr;
        if (obj.type === 'desa') selectedDesa = newArr;
      };
      // Inisialisasi DataTable
      var dt;
      if (!$.fn.DataTable.isDataTable(tableId)) {
        dt = $(tableId).DataTable({
          paging: true,
          searching: true,
          ordering: true,
          info: false,
          lengthMenu: [10, 25, 50, 100],
          columnDefs: [{
            orderable: false,
            targets: 0
          }]
        });
      } else {
        dt = $(tableId).DataTable();
      }
      // Update badge
      function updateCount() {
        $(badgeId).text(getArr().length + ' terpilih');
      }
      // Checkbox change handler
      $(tableId).on('change', 'input[type=checkbox]', function() {
        var arr = getArr();
        var val = $(this).val();
        if (this.checked) {
          if (!arr.includes(val)) arr.push(val);
        } else {
          var idx = arr.indexOf(val);
          if (idx !== -1) arr.splice(idx, 1);
        }
        setArr(arr);
        updateCount();
      });
      // Pilih Semua tombol (hanya data yang tampil di halaman aktif, hapus dulu dari array)
      $('.btn-select-all[data-table="table-' + obj.type + '"]').on('click', function() {
        var arr = getArr();
        // Ambil semua value di halaman aktif
        var currentPageVals = [];
        dt.rows({
          search: 'applied',
          page: 'current'
        }).nodes().to$().find('input[type=checkbox]').each(function() {
          currentPageVals.push($(this).val());
        });
        // Hapus dari array semua value yang ada di halaman aktif
        arr = arr.filter(function(val) {
          return !currentPageVals.includes(val);
        });
        // Tambahkan semua value di halaman aktif ke array
        currentPageVals.forEach(function(val) {
          arr.push(val);
        });
        // Set checked
        dt.rows({
          search: 'applied',
          page: 'current'
        }).nodes().to$().find('input[type=checkbox]').prop('checked', true);
        setArr(arr);
        updateCount();
      });
      // Uncheck Semua tombol (hanya data yang tampil di halaman aktif, hapus dari array)
      $('.btn-unselect-all[data-table="table-' + obj.type + '"]').on('click', function() {
        var arr = getArr();
        var currentPageVals = [];
        dt.rows({
          search: 'applied',
          page: 'current'
        }).nodes().to$().find('input[type=checkbox]').each(function() {
          currentPageVals.push($(this).val());
          this.checked = false;
        });
        // Hapus dari array semua value yang ada di halaman aktif
        arr = arr.filter(function(val) {
          return !currentPageVals.includes(val);
        });
        setArr(arr);
        updateCount();
      });
      // Saat draw ulang, set status checked sesuai array
      dt.on('draw', function() {
        var arr = getArr();
        dt.rows().nodes().to$().find('input[type=checkbox]').each(function() {
          this.checked = arr.includes($(this).val());
        });
        updateCount();
      });
      // Inisialisasi awal
      var arr = getArr();
      dt.rows().nodes().to$().find('input[type=checkbox]').each(function() {
        this.checked = arr.includes($(this).val());
      });
      updateCount();
    });

    // Submit: hanya kirim value dari array manual
    $('form').on('submit', function(e) {
      try {
        // Hapus input hidden lama
        $('input[name="blok_sensus[]"][type=hidden]').remove();
        $('input[name="sls[]"][type=hidden]').remove();
        $('input[name="desa[]"][type=hidden]').remove();
        // Tambahkan input hidden sesuai array
        selectedBlokSensus.forEach(function(val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'blok_sensus[]',
            value: val
          }).appendTo('form');
        });
        selectedSls.forEach(function(val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'sls[]',
            value: val
          }).appendTo('form');
        });
        selectedDesa.forEach(function(val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'desa[]',
            value: val
          }).appendTo('form');
        });
      } catch (error) {
        console.error('Error:', error);
        e.preventDefault();
      }
    });
  });
</script>
<script>
  $(function() {
    $('#file_import_wilkerstat').on('change', function(e) {
      var file = this.files[0];
      if (!file) return;
      var formData = new FormData();
      formData.append('file_import_wilkerstat', file);
      $('#import-wilkerstat-error').hide().text('');
      $.ajax({
        url: baseUrl + 'import-wilkerstat',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
          if (res.status === 'error') {
            var msg = res.message || (res.errors ? res.errors.join('<br>') : 'Import gagal');
            $('#import-wilkerstat-error').html(msg).show();
            return;
          }
          // Update array pilihan dan checkbox DataTables
          var data = res.data;
          // Blok Sensus
          if (typeof selectedBlokSensus !== 'undefined') selectedBlokSensus = data.blok_sensus || [];
          $('#table-blok-sensus input[type=checkbox]').each(function() {
            this.checked = selectedBlokSensus.includes($(this).val());
          });
          // SLS
          if (typeof selectedSls !== 'undefined') selectedSls = data.sls || [];
          $('#table-sls input[type=checkbox]').each(function() {
            this.checked = selectedSls.includes($(this).val());
          });
          // Desa
          if (typeof selectedDesa !== 'undefined') selectedDesa = data.desa || [];
          $('#table-desa input[type=checkbox]').each(function() {
            this.checked = selectedDesa.includes($(this).val());
          });
          // Update badge
          $('#count-blok-sensus').text(selectedBlokSensus.length + ' terpilih');
          $('#count-sls').text(selectedSls.length + ' terpilih');
          $('#count-desa').text(selectedDesa.length + ' terpilih');
          Swal.fire('Berhasil', 'Import wilkerstat berhasil, pilihan sudah diperbarui.', 'success');
        },
        error: function(xhr) {
          $('#import-wilkerstat-error').text('Terjadi error saat upload.').show();
        }
      });
      // Reset input agar bisa upload file yang sama lagi
      $(this).val('');
    });
  });
</script>
<?= $this->endSection() ?>