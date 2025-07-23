<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
  protected $table = 'desa';
  protected $primaryKey = 'uuid';
  protected $allowedFields = [
    'uuid',
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
}
