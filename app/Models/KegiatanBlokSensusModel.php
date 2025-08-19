<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanBlokSensusModel extends Model
{
  protected $table = 'kegiatan_blok_sensus';
  protected $primaryKey = 'id';
  protected $allowedFields = ['id', 'id_kegiatan', 'id_blok_sensus'];
  public $timestamps = false;
}
