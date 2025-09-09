<?php $this->extend('index'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid mt-4">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Edit Desa/Kelurahan</h3>
    </div>
    <div class="card-body">
      <form id="form-edit-desa" action="<?= base_url('desa/update/' . $desa['id']) ?>" method="post">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="provinsi">Provinsi</label>
              <select id="provinsi" class="form-control select2" name="nama_provinsi">
                <option value="<?= esc($desa['nama_provinsi']) ?>" selected><?= esc($desa['nama_provinsi']) ?></option>
              </select>
              <input type="hidden" name="kode_prov" id="kode_prov" value="<?= esc($desa['kode_prov']) ?>">
            </div>
            <div class="form-group">
              <label for="kabupaten">Kabupaten</label>
              <select id="kabupaten" class="form-control select2" name="nama_kabupaten">
                <option value="<?= esc($desa['nama_kabupaten']) ?>" selected><?= esc($desa['nama_kabupaten']) ?></option>
              </select>
              <input type="hidden" name="kode_kabupaten" id="kode_kabupaten" value="<?= esc($desa['kode_kabupaten']) ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="kecamatan">Kecamatan</label>
              <select id="kecamatan" class="form-control select2" name="nama_kecamatan">
                <option value="<?= esc($desa['nama_kecamatan']) ?>" selected><?= esc($desa['nama_kecamatan']) ?></option>
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
              <input type="hidden" name="kode_kecamatan" id="kode_kecamatan" value="<?= esc($desa['kode_kecamatan']) ?>">
            </div>
            <div class="form-group">
              <label for="kode_desa">Kode Desa</label>
              <input type="text" name="kode_desa" id="kode_desa" class="form-control" value="<?= esc($desa['kode_desa']) ?>" required>
            </div>
            <div class="form-group">
              <label for="nama_desa">Nama Desa</label>
              <input type="text" name="nama_desa" id="nama_desa" class="form-control" value="<?= esc($desa['nama_desa']) ?>" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary float-right">
              <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?= base_url('desa') ?>" class="btn btn-secondary float-right mr-2">
              <i class="fas fa-times"></i> Batal
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Select2 CSS -->
<link rel="stylesheet" href="<?= base_url('plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">

<?php include('edit_js.php'); ?>

<?php $this->endSection(); ?>
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
  $(function() {
    $('#form-edit-desa').on('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Simpan perubahan?',
        text: 'Pastikan perubahan sudah benar sebelum disimpan!',
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