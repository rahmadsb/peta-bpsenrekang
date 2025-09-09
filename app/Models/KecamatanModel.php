<?php

namespace App\Models;

use CodeIgniter\Model;

class KecamatanModel extends Model
{
  protected $table = 'kecamatan';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'kode_prov',
    'nama_provinsi',
    'kode_kabupaten',
    'nama_kabupaten',
    'kode_kecamatan',
    'nama_kecamatan'
  ];

  /**
   * Get all kecamatan based on provinsi and kabupaten
   *
   * @param string $kode_prov
   * @param string $kode_kab
   * @return array
   */
  public function getKecamatan($kode_prov, $kode_kab)
  {
    return $this->where(['kode_prov' => $kode_prov, 'kode_kabupaten' => $kode_kab])
      ->findAll();
  }

  /**
   * Get unique kecamatan data
   *
   * @return array
   */
  public function getUniqueKecamatan()
  {
    $result = [];
    $kecamatan = $this->select('kode_kecamatan, nama_kecamatan')
      ->groupBy('kode_kecamatan')
      ->findAll();

    foreach ($kecamatan as $kec) {
      $result[$kec['kode_kecamatan']] = $kec['nama_kecamatan'];
    }

    return $result;
  }

  /**
   * Check if kecamatan exists
   *
   * @param string $kode_kecamatan
   * @return bool
   */
  public function kecamatanExists($kode_kecamatan)
  {
    return $this->where('kode_kecamatan', $kode_kecamatan)->countAllResults() > 0;
  }
}
