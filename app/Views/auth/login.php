<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('css/adminlte.min.css') ?>">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Peta Kegiatan</b><br>BPS Enrekang</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Silahkan login untuk melanjutkan</p>
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger" role="alert">
            <?= session()->getFlashdata('error') ?>
          </div>
        <?php endif; ?>
        <form action="<?= base_url('login') ?>" method="post">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <!-- pengguna bisa melihat password dengan menekan tombol i -->
            <!-- on hover cursor pointer -->
            <!-- jika password show bukan icon eye tapi icon eye slash -->
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-eye" id="toggle-password" style="cursor: pointer;"></span>
              </div>
            </div>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <!-- <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">Remember Me</label>
            </div> -->
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
          </div>
        </form>
        <!-- <p class="mb-1 mt-3">
        <a href="#">I forgot my password</a>
      </p> -->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url('AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('AdminLTE-3.2.0/js/adminlte.min.js') ?>"></script>
  <script>
    $(function() {
      $('#toggle-password').on('click', function() {
        var passwordInput = $('input[name="password"]');
        if (passwordInput.attr('type') === 'password') {
          passwordInput.attr('type', 'text');
          $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          passwordInput.attr('type', 'password');
          $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });
    });
  </script>
</body>

</html>