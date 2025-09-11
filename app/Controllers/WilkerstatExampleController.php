<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class WilkerstatExampleController extends BaseController
{
  public function generateExample()
  {
    // Buat spreadsheet baru
    $spreadsheet = new Spreadsheet();

    // ===== Sheet Blok Sensus =====
    $spreadsheet->setActiveSheetIndex(0);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Blok Sensus');

    // Header
    $sheet->setCellValue('A1', 'Kode Blok Sensus');
    $sheet->setCellValue('B1', 'Nama Blok Sensus');

    // Contoh data
    $sheet->setCellValue('A2', '7371100001001');
    $sheet->setCellValue('B2', 'BS 001 DESA CONTOH');

    $sheet->setCellValue('A3', '7371100001002');
    $sheet->setCellValue('B3', 'BS 002 DESA CONTOH');

    // Format header
    $sheet->getStyle('A1:B1')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('B')->setWidth(30);

    // ===== Sheet SLS =====
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(1);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('SLS');

    // Header
    $sheet->setCellValue('A1', 'Kode SLS');
    $sheet->setCellValue('B1', 'Nama SLS');

    // Contoh data
    $sheet->setCellValue('A2', '001');
    $sheet->setCellValue('B2', 'SLS 001 DESA CONTOH');

    $sheet->setCellValue('A3', '002');
    $sheet->setCellValue('B3', 'SLS 002 DESA CONTOH');

    // Format header
    $sheet->getStyle('A1:B1')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(30);

    // ===== Sheet DESA =====
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(2);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('DESA');

    // Header
    $sheet->setCellValue('A1', 'Kode Desa');
    $sheet->setCellValue('B1', 'Nama Desa');

    // Contoh data
    $sheet->setCellValue('A2', '7371100001');
    $sheet->setCellValue('B2', 'DESA CONTOH 1');

    $sheet->setCellValue('A3', '7371100002');
    $sheet->setCellValue('B3', 'DESA CONTOH 2');

    // Format header
    $sheet->getStyle('A1:B1')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(30);

    // Aktifkan sheet pertama
    $spreadsheet->setActiveSheetIndex(0);

    // Buat file Excel
    $writer = new Xlsx($spreadsheet);
    $filename = 'contoh_import_wilkerstat.xlsx';
    $filepath = ROOTPATH . 'public/' . $filename;

    // Simpan file
    $writer->save($filepath);

    // Set header dan response
    $response = $this->response;
    $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
    $response->setHeader('Cache-Control', 'max-age=0');

    // Log informasi debugging
    log_message('info', 'Generated wilkerstat example template at: ' . $filepath);

    // Verifikasi file ada sebelum mencoba mengirimkannya
    if (file_exists($filepath)) {
      return $response->download($filepath, null);
    } else {
      log_message('error', 'Wilkerstat example file not found at: ' . $filepath);
      return $this->response->setJSON([
        'status' => 'error',
        'message' => 'File template tidak dapat dibuat. Silakan hubungi administrator.'
      ]);
    }
  }
}
