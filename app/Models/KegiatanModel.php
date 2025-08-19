<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
  protected $table = 'kegiatan';
  protected $primaryKey = 'id';
  protected $useAutoIncrement = false;
  protected $allowedFields = ['id', 'id_opsi_kegiatan', 'id_user', 'tahun', 'bulan', 'tanggal_batas_cetak', 'status', 'created_at', 'updated_at'];
  protected $useTimestamps = true;
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';
  protected $returnType = 'array';
}
