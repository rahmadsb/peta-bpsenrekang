<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Ramsey\Uuid\Uuid;

class OpsiKegiatanSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'id' => Uuid::uuid4()->toString(),
        'kode_kegiatan' => 'SP',
        'nama_kegiatan' => 'Sensus Penduduk',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'kode_kegiatan' => 'ST',
        'nama_kegiatan' => 'Sensus Pertanian',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'kode_kegiatan' => 'SE',
        'nama_kegiatan' => 'Sensus Ekonomi',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'kode_kegiatan' => 'SUSENAS',
        'nama_kegiatan' => 'Survei Sosial Ekonomi Nasional',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'kode_kegiatan' => 'SAKERNAS',
        'nama_kegiatan' => 'Survei Angkatan Kerja Nasional',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'kode_kegiatan' => 'SUPAS',
        'nama_kegiatan' => 'Survei Antar Sensus',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'kode_kegiatan' => 'SERUTI',
        'nama_kegiatan' => 'Survei Ekonomi Rumahtangga Triwulanan',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
    ];
    $this->db->table('opsi_kegiatan')->insertBatch($data);
  }
  public function down()
  {
    $this->db->table('opsi_kegiatan')->truncate();
  }
}
