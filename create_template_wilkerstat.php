<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

try {
  // Buat spreadsheet baru
  $spreadsheet = new Spreadsheet();

  // SLS Sheet
  $slsSheet = $spreadsheet->getActiveSheet();
  $slsSheet->setTitle('SLS');

  // Set header untuk SLS
  $slsSheet->setCellValue('A1', 'KODE_SLS');
  $slsSheet->setCellValue('B1', 'NAMA_SLS');

  // Beberapa data contoh
  $slsSheet->setCellValue('A2', '73160100040001');
  $slsSheet->setCellValue('B2', 'DUSUN BOTTO LIPANG');

  $slsSheet->setCellValue('A3', '73160100040003');
  $slsSheet->setCellValue('B3', 'DUSUN PARAJA');

  // Tambah sheet Blok Sensus
  $bsSheet = $spreadsheet->createSheet();
  $bsSheet->setTitle('Blok Sensus');

  // Set header untuk Blok Sensus
  $bsSheet->setCellValue('A1', 'KODE_BS');
  $bsSheet->setCellValue('B1', 'NAMA_BS');

  // Tambah sheet DESA
  $desaSheet = $spreadsheet->createSheet();
  $desaSheet->setTitle('DESA');

  // Set header untuk DESA
  $desaSheet->setCellValue('A1', 'KODE_DESA');
  $desaSheet->setCellValue('B1', 'NAMA_DESA');

  // Simpan file
  $writer = new Xlsx($spreadsheet);
  $writer->save('public/template_import_wilkerstat.xlsx');

  echo "File template_import_wilkerstat.xlsx berhasil dibuat di folder public/\n";
  echo "Format file ini dapat digunakan untuk import wilkerstat.\n";
} catch (Exception $e) {
  echo 'Error: ', $e->getMessage();
}
