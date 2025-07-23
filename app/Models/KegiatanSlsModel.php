<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanSlsModel extends Model
{
  protected $table = 'kegiatan_sls';
  protected $primaryKey = 'id';
  protected $allowedFields = ['kegiatan_uuid', 'sls_uuid'];
  public $timestamps = false;
}
