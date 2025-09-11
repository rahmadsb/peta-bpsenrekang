<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
  // Coba file yang diimpor pengguna jika ada
  $file = 'contoh_import_wilkerstat(1).xlsx';
  if (!file_exists($file)) {
    $file = 'public/contoh_import_wilkerstat.xlsx';
  }

  echo "Reading file: $file\n";

  $reader = IOFactory::createReader('Xlsx');
  $spreadsheet = $reader->load($file);

  // Periksa semua sheet yang ada
  echo "Worksheet names:\n";
  foreach ($spreadsheet->getSheetNames() as $sheetName) {
    echo "- $sheetName\n";
  }
  echo "\n";

  // Periksa data pada sheet SLS (yang ada di file pengguna)
  $worksheet = $spreadsheet->getActiveSheet();
  $highestRow = $worksheet->getHighestRow();
  $highestColumn = $worksheet->getHighestColumn();

  echo "Active sheet: " . $worksheet->getTitle() . "\n";
  echo "Total rows: $highestRow\n";
  echo "Highest column: $highestColumn\n\n";

  echo "Header row:\n";
  for ($col = 'A'; $col <= $highestColumn; $col++) {
    $cellValue = $worksheet->getCell($col . '1')->getValue();
    echo "$col: $cellValue | ";
  }
  echo "\n\n";

  echo "Data rows (first 5):\n";
  for ($row = 2; $row <= min(6, $highestRow); $row++) {
    echo "Row $row: ";
    for ($col = 'A'; $col <= $highestColumn; $col++) {
      $cellValue = $worksheet->getCell($col . $row)->getValue();
      echo "$col: $cellValue | ";
    }
    echo "\n";
  }

  // Cek database untuk beberapa kode SLS dari file
  echo "\n\nVerifikasi Database:\n";

  // Ambil 3 kode SLS pertama dari file untuk diperiksa
  $testCodes = [];
  for ($row = 2; $row <= min(4, $highestRow); $row++) {
    $testCodes[] = $worksheet->getCell('A' . $row)->getValue();
  }

  echo "Kode SLS untuk diperiksa: " . implode(", ", $testCodes) . "\n";

  // Buat koneksi database menggunakan PDO
  $db = new PDO('mysql:host=localhost;dbname=peta_bpsenrekang;charset=utf8mb4', 'root', '');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  foreach ($testCodes as $code) {
    $stmt = $db->prepare("SELECT id, kode_sls, nama_sls FROM sls WHERE kode_sls = ?");
    $stmt->execute([$code]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      echo "✓ Kode SLS '$code' ditemukan! ID: {$result['id']}, Nama: {$result['nama_sls']}\n";
    } else {
      echo "✗ Kode SLS '$code' TIDAK DITEMUKAN dalam database!\n";
    }
  }

  // Periksa masalah pada sheet yang diharapkan
  echo "\n\nAnalisis Struktur File:\n";

  // Controller mencari sheet dengan nama "Blok Sensus", "SLS", dan "DESA"
  $expectedSheets = ['Blok Sensus', 'SLS', 'DESA'];
  $foundSheets = $spreadsheet->getSheetNames();

  foreach ($expectedSheets as $expectedSheet) {
    if (in_array($expectedSheet, $foundSheets)) {
      echo "✓ Sheet '$expectedSheet' ditemukan\n";
    } else {
      echo "✗ Sheet '$expectedSheet' TIDAK DITEMUKAN! Controller membutuhkan sheet ini\n";
    }
  }
} catch (Exception $e) {
  echo 'Error: ', $e->getMessage();
}
