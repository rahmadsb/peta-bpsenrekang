<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KecamatanModel;
use App\Models\DesaModel;

class ApiController extends BaseController
{
  protected $kecamatanModel;
  protected $desaModel;

  public function __construct()
  {
    $this->kecamatanModel = new KecamatanModel();
    $this->desaModel = new DesaModel();
  }

  public function kabupaten()
  {
    $kode_prov = $this->request->getGet('kode_prov');
    if (!$kode_prov) {
      return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Kode provinsi tidak ditemukan'
      ]);
    }

    $kabupaten = get_kabupaten($kode_prov);
    return $this->response->setJSON([
      'status' => 'success',
      'data' => $kabupaten
    ]);
  }

  public function kecamatan()
  {
    $kode_prov = $this->request->getGet('kode_prov');
    $kode_kab = $this->request->getGet('kode_kab');
    if (!$kode_prov || !$kode_kab) {
      return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Kode provinsi atau kabupaten tidak ditemukan'
      ]);
    }

    // Coba dapatkan data dari database terlebih dahulu
    $dbKecamatan = [];
    $kecamatanList = $this->kecamatanModel->getKecamatan($kode_prov, $kode_kab);
    foreach ($kecamatanList as $kec) {
      $dbKecamatan[$kec['kode_kecamatan']] = $kec['nama_kecamatan'];
    }

    // Jika tidak ada data di database, gunakan data dari Excel
    if (empty($dbKecamatan)) {
      $dbKecamatan = get_kecamatan($kode_prov, $kode_kab);
    }

    return $this->response->setJSON([
      'status' => 'success',
      'data' => $dbKecamatan
    ]);
  }

  public function desa()
  {
    $kode_prov = $this->request->getGet('kode_prov');
    $kode_kab = $this->request->getGet('kode_kab');
    $kode_kec = $this->request->getGet('kode_kec');
    if (!$kode_prov || !$kode_kab || !$kode_kec) {
      return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Kode provinsi, kabupaten, atau kecamatan tidak ditemukan'
      ]);
    }

    // Coba dapatkan data dari database terlebih dahulu
    $dbDesa = [];
    $desaList = $this->desaModel->where([
      'kode_prov' => $kode_prov,
      'kode_kabupaten' => $kode_kab,
      'kode_kecamatan' => $kode_kec
    ])->findAll();

    foreach ($desaList as $desa) {
      $dbDesa[$desa['kode_desa']] = $desa['nama_desa'];
    }

    // Jika tidak ada data di database, gunakan data dari Excel
    if (empty($dbDesa)) {
      $dbDesa = get_desa($kode_prov, $kode_kab, $kode_kec);
    }

    return $this->response->setJSON([
      'status' => 'success',
      'data' => $dbDesa
    ]);
  }

  public function sls()
  {
    $kode_desa = $this->request->getGet('kode_desa');
    if (!$kode_desa) {
      return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Kode desa tidak ditemukan'
      ]);
    }

    $sls = get_sls($kode_desa);
    return $this->response->setJSON([
      'status' => 'success',
      'data' => $sls
    ]);
  }

  public function bs()
  {
    $kode_sls = $this->request->getGet('kode_sls');
    if (!$kode_sls) {
      return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Kode SLS tidak ditemukan'
      ]);
    }

    $bs = get_bs($kode_sls);
    return $this->response->setJSON([
      'status' => 'success',
      'data' => $bs
    ]);
  }
}
