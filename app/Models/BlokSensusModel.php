<?php

namespace App\Models;

use CodeIgniter\Model;

class BlokSensusModel extends Model
{
  protected $table = 'blok_sensus';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'id',
    'kode_bs',
    'nama_bs',
    'luas',
    'kode_kabupaten',
    'kode_kecamatan',
    'kode_sls',
    'nama_kabupaten',
    'nama_kecamatan',
    'nama_sls',
    'kode_desa',
    'kode_prov',
    'nama_desa',
    'nama_provinsi',
    'created_at',
    'updated_at'
  ];
  public $timestamps = true;
  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
}
