<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BlokSensusModel;
use App\Models\SlsModel;
use App\Models\DesaModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportWilkerstatController extends BaseController
{
  public function importWilkerstat()
  {
    try {
      $file = $this->request->getFile('file_import_wilkerstat');
      if (!$file->isValid()) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid.']);
      }
      $spreadsheet = IOFactory::load($file->getTempName());
      $blokSensusModel = new BlokSensusModel();
      $slsModel = new SlsModel();
      $desaModel = new DesaModel();

      $result = [
        'blok_sensus' => [],
        'sls' => [],
        'desa' => [],
        'errors' => []
      ];

      // Blok Sensus
      $sheet = $spreadsheet->getSheetByName('Blok Sensus');
      if ($sheet) {
        $rows = $sheet->toArray(null, true, true, true);
        foreach (array_slice($rows, 1) as $row) { // skip header
          $kode = $row['A'] ?? null;
          $nama = $row['B'] ?? null;
          if (!$kode) continue;
          $bs = $blokSensusModel->where('kode_bs', $kode)->first();
          if ($bs) {
            $result['blok_sensus'][] = $bs['uuid'];
          } else {
            $result['errors'][] = "Blok Sensus tidak ditemukan: $kode ($nama)";
          }
        }
      }
      // SLS
      $sheet = $spreadsheet->getSheetByName('SLS');
      if ($sheet) {
        $rows = $sheet->toArray(null, true, true, true);
        foreach (array_slice($rows, 1) as $row) {
          $kode = $row['A'] ?? null;
          $nama = $row['B'] ?? null;
          if (!$kode) continue;
          $sls = $slsModel->where('kode_sls', $kode)->first();
          if ($sls) {
            $result['sls'][] = $sls['uuid'];
          } else {
            $result['errors'][] = "SLS tidak ditemukan: $kode ($nama)";
          }
        }
      }
      // Desa
      $sheet = $spreadsheet->getSheetByName('DESA');
      if ($sheet) {
        $rows = $sheet->toArray(null, true, true, true);
        foreach (array_slice($rows, 1) as $row) {
          $kode = $row['A'] ?? null;
          $nama = $row['B'] ?? null;
          if (!$kode) continue;
          $desa = $desaModel->where('kode_desa', $kode)->first();
          if ($desa) {
            $result['desa'][] = $desa['uuid'];
          } else {
            $result['errors'][] = "Desa tidak ditemukan: $kode ($nama)";
          }
        }
      }
      if (!empty($result['errors'])) {
        return $this->response->setJSON(['status' => 'error', 'errors' => $result['errors']]);
      }
      return $this->response->setJSON(['status' => 'success', 'data' => $result]);
    } catch (\Throwable $e) {
      return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
    }
  }
}
