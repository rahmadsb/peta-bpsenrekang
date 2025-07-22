<?php
$this->extend('index');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row">
        <!-- Data Statistik -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>120</h3>
                    <p>Permintaan Data</p>
                </div>
                <div class="icon">
                    <i class="fas fa-database"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>85</h3>
                    <p>Data Terkirim</p>
                </div>
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>10</h3>
                    <p>Permintaan Diproses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>5</h3>
                    <p>Permintaan Ditolak</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Aktivitas Terbaru</h3>
        </div>
        <div class="card-body">
            <ul>
                <li>Permintaan data <b>#2024-001</b> telah dikirim ke <b>BPS Pusat</b>.</li>
                <li>Data <b>Statistik Kependudukan 2023</b> berhasil diunduh.</li>
                <li>Permintaan data <b>#2024-002</b> sedang diproses.</li>
                <li>Permintaan data <b>#2024-003</b> ditolak karena dokumen tidak lengkap.</li>
            </ul>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Ajukan Permintaan Data Baru</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <label for="judulPermintaan">Judul Permintaan</label>
                    <input type="text" class="form-control" id="judulPermintaan" placeholder="Masukkan judul permintaan">
                </div>
                <div class="form-group">
                    <label for="deskripsiPermintaan">Deskripsi</label>
                    <textarea class="form-control" id="deskripsiPermintaan" rows="3" placeholder="Jelaskan kebutuhan data Anda"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ajukan Permintaan</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>