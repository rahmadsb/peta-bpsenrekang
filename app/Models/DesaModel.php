<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
  protected $table = 'desa';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'id',
    'kode_desa',
    'nama_desa',
    'luas',
    'kode_kabupaten',
    'kode_kecamatan',
    'kode_prov',
    'nama_kabupaten',
    'nama_kecamatan',
    'nama_provinsi',
    'created_at',
    'updated_at'
  ];
  public $timestamps = true;
  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  /**
   * Get all desa based on kecamatan
   *
   * @param string $kode_kecamatan
   * @return array
   */
  public function getDesaByKecamatan($kode_kecamatan)
  {
    return $this->where('kode_kecamatan', $kode_kecamatan)
      ->findAll();
  }

  /**
   * Get unique desa data
   *
   * @return array
   */
  public function getUniqueDesa()
  {
    $result = [];
    $desa = $this->select('kode_desa, nama_desa')
      ->groupBy('kode_desa')
      ->findAll();

    foreach ($desa as $d) {
      $result[$d['kode_desa']] = $d['nama_desa'];
    }

    return $result;
  }

  /**
   * Check if desa exists
   *
   * @param string $kode_desa
   * @return bool
   */
  public function desaExists($kode_desa)
  {
    return $this->where('kode_desa', $kode_desa)->countAllResults() > 0;
  }
}
