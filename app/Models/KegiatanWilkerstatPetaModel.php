<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanWilkerstatPetaModel extends Model
{
  protected $table = 'kegiatan_wilkerstat_peta';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'id',
    'id_kegiatan',
    'wilkerstat_type',
    'id_wilkerstat',
    'jenis_peta',
    'file_path',
    'nama_file',
    'uploaded_at',
    'uploader',
    'id_parent_peta'
  ];
  public $timestamps = false;
}
