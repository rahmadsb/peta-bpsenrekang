<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
  protected $table = 'kegiatan';
  protected $primaryKey = 'uuid';
  protected $useAutoIncrement = false;
  protected $allowedFields = ['uuid', 'kode_kegiatan_option', 'id_user', 'tahun', 'bulan', 'tanggal_batas_cetak', 'status', 'created_at', 'updated_at'];
  protected $useTimestamps = true;
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';
  protected $returnType = 'array';
}
