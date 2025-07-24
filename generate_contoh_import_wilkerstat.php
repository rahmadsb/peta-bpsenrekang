<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

// Sheet 1: Blok Sensus
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Blok Sensus');
$sheet1->fromArray([
  ['KODE_BS', 'NAMA_SLS'],
  ['1101010001', 'SLS A']
]);

// Sheet 2: SLS
$sheet2 = new Worksheet($spreadsheet, 'SLS');
$sheet2->fromArray([
  ['KODE_SLS', 'NAMA_SLS'],
  ['11010101', 'SLS A']
]);
$spreadsheet->addSheet($sheet2);

// Sheet 3: DESA
$sheet3 = new Worksheet($spreadsheet, 'DESA');
$sheet3->fromArray([
  ['KODE_DESA', 'NAMA_DESA'],
  ['1101010001', 'Desa A']
]);
$spreadsheet->addSheet($sheet3);

$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__ . '/public/contoh_import_wilkerstat.xlsx');

echo "File contoh_import_wilkerstat.xlsx berhasil dibuat di folder public.\n";
