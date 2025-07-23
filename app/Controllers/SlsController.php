<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SlsModel;
use App\Models\KegiatanSlsModel;
use App\Models\KegiatanModel;
use App\Models\KegiatanWilkerstatPetaModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Uuid;

class SlsController extends BaseController
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
    $model = new SlsModel();
    $data['sls'] = $model->findAll();
    $data['title'] = 'Manajemen SLS';
    return view('sls/index', $data);
  }

  public function create()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $data['title'] = 'Tambah SLS';
    return view('sls/create', $data);
  }

  public function store()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_sls' => 'required|is_unique[sls.kode_sls]',
      'nama_sls' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      return view('sls/create', [
        'validation' => $validation
      ]);
    }
    $model = new SlsModel();
    $data = $this->request->getPost();
    $data['uuid'] = Uuid::uuid4()->toString();
    $model->insert($data);
    return redirect()->to('/sls')->with('success', 'SLS berhasil ditambahkan');
  }

  public function edit($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new SlsModel();
    $sls = $model->find($uuid);
    if (!$sls) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    $data['sls'] = $sls;
    $data['title'] = 'Edit SLS';
    return view('sls/edit', $data);
  }

  public function update($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_sls' => 'required',
      'nama_sls' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new SlsModel();
      $sls = $model->find($uuid);
      return view('sls/edit', [
        'sls' => $sls,
        'validation' => $validation
      ]);
    }
    $model = new SlsModel();
    $model->update($uuid, $this->request->getPost());
    return redirect()->to('/sls')->with('success', 'SLS berhasil diupdate');
  }

  public function delete($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new SlsModel();
    $model->delete($uuid);
    return redirect()->to('/sls')->with('success', 'SLS berhasil dihapus');
  }

  public function exportExcel()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new SlsModel();
    $data = $model->findAll();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Header
    $header = [
      'Kode SLS',
      'Nama SLS',
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
        $row['kode_sls'],
        $row['nama_sls'],
        $row['kode_desa'],
        $row['nama_desa'],
        $row['nama_kecamatan'],
        $row['nama_kabupaten'],
        $row['nama_provinsi'],
      ], null, 'A' . $rowNum);
      $rowNum++;
    }
    // Output
    $filename = 'sls_' . date('Ymd_His') . '.xlsx';
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
    $slsModel = new SlsModel();
    $sls = $slsModel->find($uuid);
    if (!$sls) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    $pivot = (new KegiatanSlsModel())->where('sls_uuid', $uuid)->findAll();
    $kegiatanModel = new KegiatanModel();
    $petaModel = new KegiatanWilkerstatPetaModel();
    $kegiatanList = [];
    foreach ($pivot as $row) {
      $kegiatan = $kegiatanModel->find($row['kegiatan_uuid']);
      if ($kegiatan) {
        $peta = $petaModel->where([
          'kegiatan_uuid' => $kegiatan['uuid'],
          'wilkerstat_type' => 'sls',
          'wilkerstat_uuid' => $uuid
        ])->findAll();
        $kegiatan['peta'] = $peta;
        $kegiatanList[] = $kegiatan;
      }
    }
    $data = [
      'sls' => $sls,
      'kegiatanList' => $kegiatanList,
      'title' => 'Detail SLS',
    ];
    return view('sls/detail', $data);
  }
}
