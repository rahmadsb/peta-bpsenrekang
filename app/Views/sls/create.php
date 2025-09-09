<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid mt-4">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Tambah SLS (Satuan Lingkungan Setempat)</h3>
    </div>
    <div class="card-body">
      <form id="form-tambah-sls" action="<?= base_url('sls/store') ?>" method="post">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="provinsi">Provinsi</label>
              <select id="provinsi" class="form-control select2" name="nama_provinsi">
                <option value="">-- Pilih Provinsi --</option>
                <option value="SULAWESI SELATAN">SULAWESI SELATAN</option>
              </select>
              <input type="hidden" name="kode_prov" id="kode_prov" value="73">
            </div>
            <div class="form-group">
              <label for="kabupaten">Kabupaten</label>
              <select id="kabupaten" class="form-control select2" name="nama_kabupaten">
                <option value="">-- Pilih Kabupaten --</option>
                <option value="KABUPATEN ENREKANG">KABUPATEN ENREKANG</option>
              </select>
              <input type="hidden" name="kode_kabupaten" id="kode_kabupaten" value="7318">
            </div>
            <div class="form-group">
              <label for="kecamatan">Kecamatan</label>
              <select id="kecamatan" class="form-control select2" name="nama_kecamatan">
                <option value="">-- Pilih Kecamatan --</option>
              </select>
              <small class="text-muted">Pilih kecamatan yang sudah ada atau tambahkan baru</small>
              <div class="mt-2" id="kecamatan-baru-container" style="display:none;">
                <div class="input-group">
                  <input type="text" id="kode_kecamatan_baru" placeholder="Kode Kecamatan Baru" class="form-control">
                  <input type="text" id="nama_kecamatan_baru" placeholder="Nama Kecamatan Baru" class="form-control">
                  <div class="input-group-append">
                    <button type="button" id="tambah-kecamatan" class="btn btn-info btn-sm">Tambahkan</button>
                  </div>
                </div>
              </div>
              <input type="hidden" name="kode_kecamatan" id="kode_kecamatan">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="desa">Desa/Kelurahan</label>
              <select id="desa" class="form-control select2" name="nama_desa">
                <option value="">-- Pilih Desa/Kelurahan --</option>
              </select>
              <small class="text-muted">Pilih desa yang sudah ada atau tambahkan baru</small>
              <div class="mt-2" id="desa-baru-container" style="display:none;">
                <div class="input-group">
                  <input type="text" id="kode_desa_baru" placeholder="Kode Desa Baru" class="form-control">
                  <input type="text" id="nama_desa_baru" placeholder="Nama Desa Baru" class="form-control">
                  <div class="input-group-append">
                    <button type="button" id="tambah-desa" class="btn btn-info btn-sm">Tambahkan</button>
                  </div>
                </div>
              </div>
              <input type="hidden" name="kode_desa" id="kode_desa">
            </div>
            <div class="form-group">
              <label for="kode_sls">Kode SLS</label>
              <input type="text" name="kode_sls" id="kode_sls" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="nama_sls">Nama SLS</label>
              <input type="text" name="nama_sls" id="nama_sls" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary float-right">
              <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?= base_url('sls') ?>" class="btn btn-secondary float-right mr-2">
              <i class="fas fa-times"></i> Batal
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- jQuery -->
<script src=<?= base_url("plugins/jquery/jquery.min.js") ?>></script>
<!-- jQuery UI 1.11.4 -->
<script src=<?= base_url("plugins/jquery-ui/jquery-ui.min.js") ?>></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src=<?= base_url("plugins/bootstrap/js/bootstrap.bundle.min.js") ?>></script>
<script src=<?= base_url("plugins/chart.js/Chart.min.js") ?>></script>
<script src=<?= base_url("plugins/sparklines/sparkline.js") ?>></script>
<script src=<?= base_url("plugins/jqvmap/jquery.vmap.min.js") ?>></script>
<script src=<?= base_url("plugins/jqvmap/maps/jquery.vmap.usa.js") ?>></script>
<script src=<?= base_url("plugins/jquery-knob/jquery.knob.min.js") ?>></script>
<script src=<?= base_url("plugins/moment/moment.min.js") ?>></script>
<script src=<?= base_url("plugins/daterangepicker/daterangepicker.js") ?>></script>
<script src=<?= base_url("plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") ?>></script>
<script src=<?= base_url("plugins/summernote/summernote-bs4.min.js") ?>></script>
<script src=<?= base_url("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js") ?>></script>
<script src=<?= base_url("js/adminlte.js") ?>></script>
<script src=<?= base_url("js/pages/dashboard.js") ?>></script>
<script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
<script src="<?= base_url('plugins/select2/js/select2.full.min.js') ?>"></script>
<script>
  $(function() {
    // Inisialisasi Select2
    $('.select2').select2({
      theme: 'bootstrap4',
      width: '100%'
    });

    // Tampilkan form tambah kecamatan baru
    $('#kecamatan').on('change', function() {
      if ($(this).val() === '') {
        $('#kecamatan-baru-container').show();
      } else {
        $('#kecamatan-baru-container').hide();
        $('#kode_kecamatan').val($(this).val());
        // Load desa berdasarkan kecamatan
        loadDesa($(this).val());
      }
    });

    // Tampilkan form tambah desa baru
    $('#desa').on('change', function() {
      if ($(this).val() === '') {
        $('#desa-baru-container').show();
      } else {
        $('#desa-baru-container').hide();
        $('#kode_desa').val($(this).val());
      }
    });

    // Tambah kecamatan baru
    $('#tambah-kecamatan').on('click', function() {
      const kode = $('#kode_kecamatan_baru').val();
      const nama = $('#nama_kecamatan_baru').val();

      if (!kode || !nama) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Kode dan nama kecamatan harus diisi!'
        });
        return;
      }

      // Tambahkan opsi baru ke dropdown kecamatan
      const newOption = new Option(kode + ' - ' + nama, kode, true, true);
      $('#kecamatan').append(newOption).trigger('change');
      $('#kode_kecamatan').val(kode);
      $('#kecamatan-baru-container').hide();

      // Reset form
      $('#kode_kecamatan_baru').val('');
      $('#nama_kecamatan_baru').val('');
    });

    // Tambah desa baru
    $('#tambah-desa').on('click', function() {
      const kode = $('#kode_desa_baru').val();
      const nama = $('#nama_desa_baru').val();

      if (!kode || !nama) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Kode dan nama desa harus diisi!'
        });
        return;
      }

      // Tambahkan opsi baru ke dropdown desa
      const newOption = new Option(kode + ' - ' + nama, kode, true, true);
      $('#desa').append(newOption).trigger('change');
      $('#kode_desa').val(kode);
      $('#desa-baru-container').hide();

      // Reset form
      $('#kode_desa_baru').val('');
      $('#nama_desa_baru').val('');
    });

    // Load kecamatan berdasarkan provinsi dan kabupaten
    function loadKecamatan() {
      const kode_prov = $('#kode_prov').val();
      const kode_kab = $('#kode_kabupaten').val();

      if (kode_prov && kode_kab) {
        $.ajax({
          url: '<?= base_url('api/kecamatan') ?>',
          type: 'GET',
          data: {
            kode_prov,
            kode_kab
          },
          dataType: 'json',
          success: function(res) {
            if (res.status === 'success') {
              $('#kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>');
              $.each(res.data, function(kode, nama) {
                $('#kecamatan').append(`<option value="${kode}">${kode} - ${nama}</option>`);
              });
              $('#kecamatan').append('<option value="">+ Tambah Kecamatan Baru</option>');
            }
          }
        });
      }
    }

    // Load desa berdasarkan kecamatan
    function loadDesa(kode_kec) {
      if (kode_kec) {
        const kode_prov = $('#kode_prov').val();
        const kode_kab = $('#kode_kabupaten').val();

        $.ajax({
          url: '<?= base_url('api/desa') ?>',
          type: 'GET',
          data: {
            kode_prov,
            kode_kab,
            kode_kec
          },
          dataType: 'json',
          success: function(res) {
            if (res.status === 'success') {
              $('#desa').empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');
              $.each(res.data, function(kode, nama) {
                $('#desa').append(`<option value="${kode}">${kode} - ${nama}</option>`);
              });
              $('#desa').append('<option value="">+ Tambah Desa/Kelurahan Baru</option>');
            }
          }
        });
      }
    }

    // Load kecamatan pada saat halaman dimuat
    loadKecamatan();

    // Validasi form sebelum submit
    $('#form-tambah-sls').on('submit', function(e) {
      e.preventDefault();

      // Validasi field yang diperlukan
      if (!$('#kode_sls').val() || !$('#nama_sls').val()) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Kode SLS dan Nama SLS harus diisi!',
          confirmButtonText: 'OK'
        });
        return false;
      }

      Swal.fire({
        title: 'Simpan data?',
        text: 'Pastikan data sudah benar sebelum disimpan!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
</script>
<?php $this->endSection(); ?>