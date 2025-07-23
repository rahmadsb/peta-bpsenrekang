<?= $this->extend('index') ?>

<?= $this->section('content') ?>
<div class="container">
  <h1>Dashboard Admin</h1>
  <p>Selamat datang di dashboard admin</p>
  <a href="<?= base_url('ipds') ?>" class="btn btn-primary">Ke Dashboard IPDS</a>
  <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
</div>
<?= $this->endSection() ?>