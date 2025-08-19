<?php

namespace App\Models;

use CodeIgniter\Model;

class OpsiKegiatanModel extends Model
{
  protected $table = 'opsi_kegiatan';
  protected $primaryKey = 'id';
  protected $useAutoIncrement = false;
  protected $allowedFields = ['id', 'kode_kegiatan', 'nama_kegiatan', 'created_at', 'updated_at'];
  protected $useTimestamps = true;
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';
  protected $returnType = 'array';
}
