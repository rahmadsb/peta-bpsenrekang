<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DesaModel;
use App\Models\KegiatanDesaModel;
use App\Models\KegiatanModel;
use App\Models\KegiatanWilkerstatPetaModel;
use App\Models\OpsiKegiatanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Uuid;

class DesaController extends BaseController
{
  private function checkAccess()
  {
    if (!session()->get('logged_in')) {
      return redirect()->to('/login');
    }
    $role = session('role');
    if ($role !== 'ADMIN' && $role !== 'IPDS') {
      return redirect()->to('/');
    }
    return null;
  }

  public function index()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new DesaModel();
    $data['desa'] = $model->findAll();
    $data['title'] = 'Manajemen Desa';
    return view('desa/index', $data);
  }

  public function create()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $data['title'] = 'Tambah Desa';
    return view('desa/create', $data);
  }

  public function store()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_desa' => 'required|is_unique[desa.kode_desa]',
      'nama_desa' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      return view('desa/create', [
        'validation' => $validation
      ]);
    }
    $model = new DesaModel();
    $data = $this->request->getPost();
    $data['uuid'] = Uuid::uuid4()->toString();
    $model->insert($data);
    return redirect()->to('/desa')->with('success', 'Desa berhasil ditambahkan');
  }

  public function edit($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new DesaModel();
    $desa = $model->find($uuid);
    if (!$desa) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    $data['desa'] = $desa;
    $data['title'] = 'Edit Desa';
    return view('desa/edit', $data);
  }

  public function update($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_desa' => 'required',
      'nama_desa' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new DesaModel();
      $desa = $model->find($uuid);
      return view('desa/edit', [
        'desa' => $desa,
        'validation' => $validation
      ]);
    }
    $model = new DesaModel();
    $model->update($uuid, $this->request->getPost());
    return redirect()->to('/desa')->with('success', 'Desa berhasil diupdate');
  }

  public function delete($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new DesaModel();
    $model->delete($uuid);
    return redirect()->to('/desa')->with('success', 'Desa berhasil dihapus');
  }

  public function exportExcel()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new DesaModel();
    $data = $model->findAll();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Header
    $header = [
      'Kode Desa',
      'Nama Desa',
      'Kecamatan',
      'Kabupaten',
      'Provinsi'
    ];
    $sheet->fromArray($header, null, 'A1');
    // Data
    $rowNum = 2;
    foreach ($data as $row) {
      $sheet->fromArray([
        $row['kode_desa'],
        $row['nama_desa'],
        $row['nama_kecamatan'],
        $row['nama_kabupaten'],
        $row['nama_provinsi'],
      ], null, 'A' . $rowNum);
      $rowNum++;
    }
    // Output
    $filename = 'desa_' . date('Ymd_His') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
  }

  public function importExcel()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    // Implementasi impor Excel di sini
  }

  public function detail($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $desaModel = new DesaModel();
    $desa = $desaModel->find($uuid);
    if (!$desa) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    $pivot = (new KegiatanDesaModel())->where('id_desa', $uuid)->findAll();
    $kegiatanModel = new KegiatanModel();
    $petaModel = new KegiatanWilkerstatPetaModel();
    $kegiatanList = [];
    foreach ($pivot as $row) {
      $kegiatan = $kegiatanModel->find($row['id_kegiatan']);
      if ($kegiatan) {
        $peta = $petaModel->where([
          'id_kegiatan' => $kegiatan['id'],
          'wilkerstat_type' => 'desa',
          'id_wilkerstat' => $uuid
        ])->findAll();
        $kegiatan['peta'] = $peta;
        $kegiatanList[] = $kegiatan;
      }
    }

    $daftarKegiatan = [];
    $kegiatanOptionModel = new OpsiKegiatanModel();
    foreach ($kegiatanList as $key => $value) {
      $kegiatanOption = $kegiatanOptionModel->find($value['id_opsi_kegiatan']);
      $daftarKegiatan[] = [
        'nama_kegiatan' => $kegiatanOption['nama_kegiatan'] . ' ' . $value['tahun'] . ' ' . $value['bulan'],
        'peta' => $value['peta'],
        'tahun' => $value['tahun'],
        'bulan' => $value['bulan'],
        'status' => $value['status'],
        'id_user' => $value['id_user'],
        'uuid' => $value['id'],
        'id_opsi_kegiatan' => $value['id_opsi_kegiatan'],
        'nama_opsi_kegiatan' => $kegiatanOption['nama_kegiatan'],
      ];
    }

    $data = [
      'desa' => $desa,
      'daftarKegiatan' => $daftarKegiatan,
      'title' => 'Detail Desa',
    ];
    return view('desa/detail', $data);
  }
}
