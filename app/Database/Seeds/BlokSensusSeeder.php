<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Uuid;

class BlokSensusSeeder extends Seeder
{
  public function run()
  {
    $filePath = ROOTPATH . 'kode_bs.xlsx';
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    $header = array_map('strtolower', $rows[1]);
    $data = [];
    $now = date('Y-m-d H:i:s');

    foreach (array_slice($rows, 1) as $row) {
      // Map kolom sesuai header
      $rowAssoc = array_combine($header, $row);
      $data[] = [
        'uuid' => Uuid::uuid4()->toString(),
        'kode_bs' => $rowAssoc['idbs'],
        'nama_bs' => $rowAssoc['kdbs'],
        'luas' => $rowAssoc['luas'],
        'kode_kabupaten' => $rowAssoc['kdkab'],
        'kode_kecamatan' => $rowAssoc['kdkec'],
        'kode_sls' => $rowAssoc['kdsls'],
        'nama_kabupaten' => $rowAssoc['nmkab'],
        'nama_kecamatan' => $rowAssoc['nmkec'],
        'nama_sls' => $rowAssoc['nmsls'],
        'kode_desa' => $rowAssoc['kddesa'],
        'kode_prov' => $rowAssoc['kdprov'],
        'nama_desa' => $rowAssoc['nmdesa'],
        'nama_provinsi' => $rowAssoc['nmprov'],
        'created_at' => $now,
        'updated_at' => $now,
      ];
    }

    $this->db->table('blok_sensus')->insertBatch($data);
  }
}
