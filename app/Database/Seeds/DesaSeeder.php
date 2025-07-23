<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Uuid;

class DesaSeeder extends Seeder
{
  public function run()
  {
    $filePath = ROOTPATH . 'kode_sls.xlsx';
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    $header = array_map('strtolower', $rows[1]);
    $desaMap = [];
    $now = date('Y-m-d H:i:s');

    foreach (array_slice($rows, 1) as $row) {
      $rowAssoc = array_combine($header, $row);
      $kode_desa = $rowAssoc['idsls']; // Perbaikan: gunakan idsls sebagai kode_desa
      if (!isset($desaMap[$kode_desa])) {
        $desaMap[$kode_desa] = [
          'uuid' => Uuid::uuid4()->toString(),
          'kode_desa' => $rowAssoc['idsls'],
          'nama_desa' => $rowAssoc['nmdesa'],
          'luas' => $rowAssoc['luas'],
          'kode_kabupaten' => $rowAssoc['kdkab'],
          'kode_kecamatan' => $rowAssoc['kdkec'],
          'kode_prov' => $rowAssoc['kdprov'],
          'nama_kabupaten' => $rowAssoc['nmkab'],
          'nama_kecamatan' => $rowAssoc['nmkec'],
          'nama_provinsi' => $rowAssoc['nmprov'],
          'created_at' => $now,
          'updated_at' => $now,
        ];
      }
    }

    $this->db->table('desa')->insertBatch(array_values($desaMap));
  }
}
