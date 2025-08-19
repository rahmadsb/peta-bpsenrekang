<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BlokSensusModel;
use App\Models\KegiatanBlokSensusModel;
use App\Models\KegiatanModel;
use App\Models\KegiatanWilkerstatPetaModel;
use App\Models\OpsiKegiatanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Uuid;

class BlokSensusController extends BaseController
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
    $model = new BlokSensusModel();
    $data['blokSensus'] = $model->findAll();
    $data['title'] = 'Manajemen Blok Sensus';
    return view('blok_sensus/index', $data);
  }

  public function create()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $data['title'] = 'Tambah Blok Sensus';
    return view('blok_sensus/create', $data);
  }

  public function store()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_bs' => 'required|is_unique[blok_sensus.kode_bs]',
      'nama_bs' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      return view('blok_sensus/create', [
        'validation' => $validation
      ]);
    }
    $model = new BlokSensusModel();
    $data = $this->request->getPost();
    $data['uuid'] = Uuid::uuid4()->toString();
    $model->insert($data);
    return redirect()->to('/blok-sensus')->with('success', 'Blok sensus berhasil ditambahkan');
  }

  public function edit($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new BlokSensusModel();
    $blok = $model->find($uuid);
    if (!$blok) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    $data['blok'] = $blok;
    $data['title'] = 'Edit Blok Sensus';
    return view('blok_sensus/edit', $data);
  }

  public function update($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_bs' => 'required',
      'nama_bs' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new BlokSensusModel();
      $blok = $model->find($uuid);
      return view('blok_sensus/edit', [
        'blok' => $blok,
        'validation' => $validation
      ]);
    }
    $model = new BlokSensusModel();
    $model->update($uuid, $this->request->getPost());
    return redirect()->to('/blok-sensus')->with('success', 'Blok sensus berhasil diupdate');
  }

  public function delete($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new BlokSensusModel();
    $model->delete($uuid);
    return redirect()->to('/blok-sensus')->with('success', 'Blok sensus berhasil dihapus');
  }

  public function exportExcel()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new BlokSensusModel();
    $data = $model->findAll();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Header
    $header = [
      'Kode BS',
      'Nama BS',
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
        $row['kode_bs'],
        $row['nama_bs'],
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
    $filename = 'blok_sensus_' . date('Ymd_His') . '.xlsx';
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
    $blokModel = new BlokSensusModel();
    $blok = $blokModel->find($uuid);
    if (!$blok) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    $pivot = (new KegiatanBlokSensusModel())->where('id_blok_sensus', $uuid)->findAll();
    $kegiatanModel = new KegiatanModel();
    $petaModel = new KegiatanWilkerstatPetaModel();
    $kegiatanList = [];
    foreach ($pivot as $row) {
      $kegiatan = $kegiatanModel->find($row['id_kegiatan']);
      if ($kegiatan) {
        // Ambil file peta untuk blok sensus ini pada kegiatan ini
        $peta = $petaModel->where([
          'id_kegiatan' => $kegiatan['id'],
          'wilkerstat_type' => 'blok_sensus',
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
      'blok' => $blok,
      'daftarKegiatan' => $daftarKegiatan,
      'title' => 'Detail Blok Sensus',
    ];
    // atur setiap kegiatanList['nama_kegiatan'] dengan nama_kegiatan
    // ambil nama kegiatan pada opsi kegiatan
    return view('blok_sensus/detail', $data);
  }
}
