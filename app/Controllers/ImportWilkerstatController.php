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
      // Validasi file upload
      $file = $this->request->getFile('file_import_wilkerstat');
      if (!$file || !$file->isValid()) {
        return $this->response->setJSON([
          'status' => 'error',
          'message' => 'File tidak valid atau tidak ditemukan'
        ]);
      }

      // Validasi mime type (Excel)
      $validMimes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
      if (!in_array($file->getMimeType(), $validMimes)) {
        return $this->response->setJSON([
          'status' => 'error',
          'message' => 'Format file tidak valid. Harap upload file Excel (.xls atau .xlsx)'
        ]);
      }

      // Load spreadsheet
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
        foreach (array_slice($rows, 1) as $index => $row) { // skip header
          $rowNum = $index + 2; // +2 karena index dimulai dari 0 dan kita skip header
          $kode = trim($row['A'] ?? '');
          $nama = trim($row['B'] ?? '');

          if (empty($kode)) {
            continue; // Skip baris kosong
          }

          $bs = $blokSensusModel->where('kode_bs', $kode)->first();
          if ($bs) {
            $result['blok_sensus'][] = $bs['id'];
          } else {
            $result['errors'][] = "Baris $rowNum: Blok Sensus dengan kode '$kode' ($nama) tidak ditemukan dalam database";
          }
        }
      } else {
        $result['errors'][] = "Sheet 'Blok Sensus' tidak ditemukan dalam file Excel";
      }

      // SLS
      $sheet = $spreadsheet->getSheetByName('SLS');
      if ($sheet) {
        $rows = $sheet->toArray(null, true, true, true);
        foreach (array_slice($rows, 1) as $index => $row) {
          $rowNum = $index + 2;
          $kode = trim($row['A'] ?? '');
          $nama = trim($row['B'] ?? '');

          if (empty($kode)) {
            continue;
          }

          $sls = $slsModel->where('kode_sls', $kode)->first();
          if ($sls) {
            $result['sls'][] = $sls['id'];
          } else {
            $result['errors'][] = "Baris $rowNum: SLS dengan kode '$kode' ($nama) tidak ditemukan dalam database";
          }
        }
      }

      // Desa
      $sheet = $spreadsheet->getSheetByName('DESA');
      if ($sheet) {
        $rows = $sheet->toArray(null, true, true, true);
        foreach (array_slice($rows, 1) as $index => $row) {
          $rowNum = $index + 2;
          $kode = trim($row['A'] ?? '');
          $nama = trim($row['B'] ?? '');

          if (empty($kode)) {
            continue;
          }

          $desa = $desaModel->where('kode_desa', $kode)->first();
          if ($desa) {
            $result['desa'][] = $desa['id'];
          } else {
            $result['errors'][] = "Baris $rowNum: Desa dengan kode '$kode' ($nama) tidak ditemukan dalam database";
          }
        }
      }

      // Tampilkan error jika ada
      if (!empty($result['errors'])) {
        return $this->response->setJSON([
          'status' => 'error',
          'errors' => $result['errors']
        ]);
      }

      // Hapus duplikat
      $result['blok_sensus'] = array_values(array_unique($result['blok_sensus']));
      $result['sls'] = array_values(array_unique($result['sls']));
      $result['desa'] = array_values(array_unique($result['desa']));

      // Jika tidak ada data yang ditemukan
      if (empty($result['blok_sensus']) && empty($result['sls']) && empty($result['desa'])) {
        return $this->response->setJSON([
          'status' => 'error',
          'message' => 'Tidak ada data wilkerstat yang valid ditemukan dalam file Excel'
        ]);
      }

      // Log informasi debugging
      log_message('debug', 'Import wilkerstat successful: ' . json_encode($result));

      return $this->response->setJSON([
        'status' => 'success',
        'data' => $result,
        'message' => 'Import wilkerstat berhasil'
      ]);
    } catch (\Throwable $e) {
      log_message('error', 'Error importing wilkerstat: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
      return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }
}
