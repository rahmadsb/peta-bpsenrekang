<?= $this->extend('index') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
  <h2>Edit User</h2>
  <form action="<?= base_url('user/update/' . $user['id']) ?>" method="post">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control<?= isset($validation) && $validation->hasError('username') ? ' is-invalid' : '' ?>" id="username" name="username" value="<?= old('username', $user['username']) ?>" required minlength="3">
      <?php if (isset($validation) && $validation->hasError('username')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('username') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password (kosongkan jika tidak diubah)</label>
      <input type="password" class="form-control<?= isset($validation) && $validation->hasError('password') ? ' is-invalid' : '' ?>" id="password" name="password" minlength="6">
      <?php if (isset($validation) && $validation->hasError('password')): ?>
        <div class="invalid-feedback">
          <?= $validation->getError('password') ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="role" class="form-label">Role</label>
      <select class="form-control" id="role" name="role" required>
        <option value="ADMIN" <?= $user['role'] === 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
        <option value="SUBJECT_MATTER" <?= $user['role'] === 'SUBJECT_MATTER' ? 'selected' : '' ?>>SUBJECT_MATTER</option>
        <option value="IPDS" <?= $user['role'] === 'IPDS' ? 'selected' : '' ?>>IPDS</option>
        <option value="GUEST" <?= $user['role'] === 'GUEST' ? 'selected' : '' ?>>GUEST</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('user') ?>" class="btn btn-secondary">Batal</a>
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
<?= $this->endSection() ?>