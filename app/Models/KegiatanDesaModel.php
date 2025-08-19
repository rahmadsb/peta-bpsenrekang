<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanDesaModel extends Model
{
  protected $table = 'kegiatan_desa';
  protected $primaryKey = 'id';
  protected $allowedFields = ['id', 'id_kegiatan', 'id_desa'];
  public $timestamps = false;
}
