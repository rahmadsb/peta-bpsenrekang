<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" id="darkModeToggle" href="#" role="button" title="Switch Light/Dark Mode">
        <i class="fas fa-moon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
        <i class="fas fa-th-large"></i>
      </a>
    </li>
    <!-- dropdown menu for user -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-user"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">User</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= base_url('logout') ?>" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('darkModeToggle');
    const body = document.body;
    // Set mode on load
    if (localStorage.getItem('theme') === 'dark') {
      body.classList.add('dark-mode');
    }
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      body.classList.toggle('dark-mode');
      if (body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
      } else {
        localStorage.setItem('theme', 'light');
      }
    });
  });
</script>