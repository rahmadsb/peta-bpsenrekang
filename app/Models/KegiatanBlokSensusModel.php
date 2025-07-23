<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanBlokSensusModel extends Model
{
  protected $table = 'kegiatan_blok_sensus';
  protected $primaryKey = 'id';
  protected $allowedFields = ['kegiatan_uuid', 'blok_sensus_uuid'];
  public $timestamps = false;
}
