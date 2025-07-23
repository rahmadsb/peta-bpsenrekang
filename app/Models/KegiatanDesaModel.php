<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanDesaModel extends Model
{
  protected $table = 'kegiatan_desa';
  protected $primaryKey = 'id';
  protected $allowedFields = ['kegiatan_uuid', 'desa_uuid'];
  public $timestamps = false;
}
