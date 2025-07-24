<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src=<?= base_url("img/AdminLTELogo.png") ?> alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">PETAKANG</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src=<?= base_url("img/user2-160x160.jpg") ?> class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= session()->get('username') ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <?php
        $uri = service('uri');
        $currentPath = $uri->getPath();
        $currentUrl = current_url();
        ?>
        <li class="nav-item">
          <a href="<?= base_url('/') ?>" class="nav-link <?= ($currentPath === '/' || $currentPath === '/admin' || $currentPath === '/ipds' || $currentPath === '/subject-matter' || $currentPath === '/guest') ? ' active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('user') ?>" class="nav-link<?= (strpos($currentPath, '/user') === 0) ? ' active' : '' ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>Manajemen User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('kegiatan-option') ?>" class="nav-link<?= ($currentPath === '/kegiatan-option') ? ' active' : '' ?>">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Opsi Kegiatan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('kegiatan') ?>" class="nav-link<?= ($currentPath === '/kegiatan') ? ' active' : '' ?>">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>Manajemen Kegiatan</p>
          </a>
        </li>
        <?php if (in_array(session('role'), ['ADMIN', 'IPDS'])): ?>
          <li class="nav-item has-treeview<?= (strpos($currentUrl, base_url('blok-sensus')) === 0 || strpos($currentUrl, base_url('sls')) === 0 || strpos($currentUrl, base_url('desa')) === 0) ? ' menu-open' : '' ?>">
            <a href="#" class="nav-link<?= (strpos($currentUrl, base_url('blok-sensus')) === 0 || strpos($currentUrl, base_url('sls')) === 0 || strpos($currentUrl, base_url('desa')) === 0) ? ' active' : '' ?>">
              <i class="nav-icon fas fa-map"></i>
              <p>
                Wilkerstat
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= base_url('blok-sensus') ?>" class="nav-link<?= (strpos($currentUrl, base_url('blok-sensus')) === 0) ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blok Sensus</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('sls') ?>" class="nav-link<?= (strpos($currentUrl, base_url('sls')) === 0) ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SLS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('desa') ?>" class="nav-link<?= (strpos($currentUrl, base_url('desa')) === 0) ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Desa</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>