<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KegiatanModel;
use App\Models\KegiatanOptionModel;
use Ramsey\Uuid\Uuid;
use App\Models\KegiatanBlokSensusModel;
use App\Models\KegiatanSlsModel;
use App\Models\KegiatanDesaModel;
use App\Models\BlokSensusModel;
use App\Models\SlsModel;
use App\Models\DesaModel;

class KegiatanController extends BaseController
{
  private function canManage()
  {
    $role = session('role');
    return in_array($role, ['ADMIN', 'IPDS', 'SUBJECT_MATTER']);
  }

  private $bulanList = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  ];

  public function index()
  {
    $model = new KegiatanModel();
    $optionModel = new KegiatanOptionModel();
    $data['kegiatan'] = $model->findAll();
    $opsi = $optionModel->findAll();
    $opsiMap = [];
    foreach ($opsi as $o) {
      $opsiMap[$o['uuid']] = $o['nama_kegiatan'];
    }
    $data['opsiMap'] = $opsiMap;
    $data['canManage'] = $this->canManage();
    $data['title'] = 'Kegiatan';
    $data['bulanList'] = $this->bulanList;
    return view('kegiatan/index', $data);
  }

  public function create()
  {
    if (!$this->canManage()) return redirect()->to('/kegiatan');
    $optionModel = new KegiatanOptionModel();
    $data['opsi'] = $optionModel->findAll();
    $data['title'] = 'Tambah Kegiatan';
    $data['bulanList'] = $this->bulanList;
    $data['blokSensusList'] = (new BlokSensusModel())->findAll();
    $data['slsList'] = (new SlsModel())->findAll();
    $data['desaList'] = (new DesaModel())->findAll();
    return view('kegiatan/create', $data);
  }

  public function store()
  {
    // dd($_POST);
    if (!$this->canManage()) return redirect()->to('/kegiatan');
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_kegiatan_option' => 'required',
      'tahun' => 'required|numeric',
      'bulan' => 'required',
      'tanggal_batas_cetak' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $optionModel = new KegiatanOptionModel();
      $data['opsi'] = $optionModel->findAll();
      $data['validation'] = $validation;
      $data['bulanList'] = $this->bulanList;
      $data['blokSensusList'] = (new BlokSensusModel())->findAll();
      $data['slsList'] = (new SlsModel())->findAll();
      $data['desaList'] = (new DesaModel())->findAll();
      return view('kegiatan/create', $data);
    }
    $role = session('role');
    $status = 'disiapkan (IPDS)';
    if ($role === 'SUBJECT_MATTER') $status = 'digunakan (SM)';
    $model = new KegiatanModel();
    $uuid = Uuid::uuid4()->toString();
    $model->insert([
      'uuid' => $uuid,
      'kode_kegiatan_option' => $this->request->getPost('kode_kegiatan_option'),
      'id_user' => session('user_id'),
      'tahun' => $this->request->getPost('tahun'),
      'bulan' => $this->request->getPost('bulan'),
      'tanggal_batas_cetak' => $this->request->getPost('tanggal_batas_cetak'),
      'status' => $status,
    ]);
    // Simpan relasi wilkerstat
    $bsModel = new KegiatanBlokSensusModel();
    $slsModel = new KegiatanSlsModel();
    $desaModel = new KegiatanDesaModel();
    foreach (array_unique($this->request->getPost('blok_sensus') ?? []) as $bs) {
      $bsModel->insert(['kegiatan_uuid' => $uuid, 'blok_sensus_uuid' => $bs]);
    }
    foreach (array_unique($this->request->getPost('sls') ?? []) as $sls) {
      $slsModel->insert(['kegiatan_uuid' => $uuid, 'sls_uuid' => $sls]);
    }
    foreach (array_unique($this->request->getPost('desa') ?? []) as $desa) {
      $desaModel->insert(['kegiatan_uuid' => $uuid, 'desa_uuid' => $desa]);
    }
    return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil ditambahkan');
  }

  public function edit($uuid)
  {
    if (!$this->canManage()) return redirect()->to('/kegiatan');
    $model = new KegiatanModel();
    $optionModel = new KegiatanOptionModel();
    $kegiatan = $model->find($uuid);
    if (!$kegiatan) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    $data['kegiatan'] = $kegiatan;
    $data['opsi'] = $optionModel->findAll();
    $data['statusList'] = [
      'disiapkan (IPDS)',
      'digunakan (SM)',
      'scan peta (IPDS)',
      'upload peta (IPDS)',
      'selesai'
    ];
    $data['title'] = 'Edit Kegiatan';
    $data['bulanList'] = $this->bulanList;
    $data['blokSensusList'] = (new BlokSensusModel())->findAll();
    $data['slsList'] = (new SlsModel())->findAll();
    $data['desaList'] = (new DesaModel())->findAll();
    $data['selectedBlokSensus'] = array_column((new KegiatanBlokSensusModel())->where('kegiatan_uuid', $uuid)->findAll(), 'blok_sensus_uuid');
    $data['selectedSls'] = array_column((new KegiatanSlsModel())->where('kegiatan_uuid', $uuid)->findAll(), 'sls_uuid');
    $data['selectedDesa'] = array_column((new KegiatanDesaModel())->where('kegiatan_uuid', $uuid)->findAll(), 'desa_uuid');
    return view('kegiatan/edit', $data);
  }

  public function update($uuid)
  {
    if (!$this->canManage()) return redirect()->to('/kegiatan');
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_kegiatan_option' => 'required',
      'tahun' => 'required|numeric',
      'bulan' => 'required',
      'tanggal_batas_cetak' => 'required',
      'status' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new KegiatanModel();
      $optionModel = new KegiatanOptionModel();
      $kegiatan = $model->find($uuid);
      $data['kegiatan'] = $kegiatan;
      $data['opsi'] = $optionModel->findAll();
      $data['statusList'] = [
        'disiapkan (IPDS)',
        'digunakan (SM)',
        'scan peta (IPDS)',
        'upload peta (IPDS)',
        'selesai'
      ];
      $data['validation'] = $validation;
      $data['title'] = 'Edit Kegiatan';
      $data['bulanList'] = $this->bulanList;
      $data['blokSensusList'] = (new BlokSensusModel())->findAll();
      $data['slsList'] = (new SlsModel())->findAll();
      $data['desaList'] = (new DesaModel())->findAll();
      $data['selectedBlokSensus'] = array_column((new KegiatanBlokSensusModel())->where('kegiatan_uuid', $uuid)->findAll(), 'blok_sensus_uuid');
      $data['selectedSls'] = array_column((new KegiatanSlsModel())->where('kegiatan_uuid', $uuid)->findAll(), 'sls_uuid');
      $data['selectedDesa'] = array_column((new KegiatanDesaModel())->where('kegiatan_uuid', $uuid)->findAll(), 'desa_uuid');
      return view('kegiatan/edit', $data);
    }
    $model = new KegiatanModel();
    $model->update($uuid, [
      'kode_kegiatan_option' => $this->request->getPost('kode_kegiatan_option'),
      'tahun' => $this->request->getPost('tahun'),
      'bulan' => $this->request->getPost('bulan'),
      'tanggal_batas_cetak' => $this->request->getPost('tanggal_batas_cetak'),
      'status' => $this->request->getPost('status'),
    ]);
    // Update relasi wilkerstat
    $bsModel = new KegiatanBlokSensusModel();
    $slsModel = new KegiatanSlsModel();
    $desaModel = new KegiatanDesaModel();
    $bsModel->where('kegiatan_uuid', $uuid)->delete();
    $slsModel->where('kegiatan_uuid', $uuid)->delete();
    $desaModel->where('kegiatan_uuid', $uuid)->delete();
    foreach (array_unique($this->request->getPost('blok_sensus') ?? []) as $bs) {
      $bsModel->insert(['kegiatan_uuid' => $uuid, 'blok_sensus_uuid' => $bs]);
    }
    foreach (array_unique($this->request->getPost('sls') ?? []) as $sls) {
      $slsModel->insert(['kegiatan_uuid' => $uuid, 'sls_uuid' => $sls]);
    }
    foreach (array_unique($this->request->getPost('desa') ?? []) as $desa) {
      $desaModel->insert(['kegiatan_uuid' => $uuid, 'desa_uuid' => $desa]);
    }
    return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil diupdate');
  }

  public function delete($uuid)
  {
    if (!$this->canManage()) return redirect()->to('/kegiatan');
    $model = new KegiatanModel();
    $model->delete($uuid);
    return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil dihapus');
  }
}
