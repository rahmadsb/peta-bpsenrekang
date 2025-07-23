<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanOptionModel extends Model
{
  protected $table = 'kegiatan_option';
  protected $primaryKey = 'uuid';
  protected $useAutoIncrement = false;
  protected $allowedFields = ['uuid', 'kode_kegiatan', 'nama_kegiatan', 'created_at', 'updated_at'];
  protected $useTimestamps = true;
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';
  protected $returnType = 'array';
}
