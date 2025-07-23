<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanWilkerstatPetaModel extends Model
{
  protected $table = 'kegiatan_wilkerstat_peta';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'kegiatan_uuid',
    'wilkerstat_type',
    'wilkerstat_uuid',
    'jenis_peta',
    'file_path',
    'nama_file',
    'uploaded_at',
    'uploader',
    'parent_peta_id'
  ];
  public $timestamps = false;
}
