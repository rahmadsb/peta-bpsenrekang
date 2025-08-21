<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KegiatanModel;
use App\Models\OpsiKegiatanModel;
use App\Models\UserModel;
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

  private function canView()
  {
    $role = session('role');
    return in_array($role, ['ADMIN', 'IPDS', 'SUBJECT_MATTER', 'GUEST']);
  }

  private function canManageKegiatan($kegiatanId = null)
  {
    $role = session('role');
    $userId = session('user_id');

    // ADMIN dan IPDS bisa manage semua kegiatan
    if (in_array($role, ['ADMIN', 'IPDS'])) {
      return true;
    }

    // SUBJECT_MATTER hanya bisa manage kegiatan yang dibuat olehnya
    if ($role === 'SUBJECT_MATTER') {
      if ($kegiatanId) {
        $kegiatanModel = new KegiatanModel();
        $kegiatan = $kegiatanModel->find($kegiatanId);
        return $kegiatan && $kegiatan['id_user'] === $userId;
      }
      return true; // Untuk create kegiatan baru
    }

    return false;
  }

  private function canChangeStatus($kegiatanId = null)
  {
    $role = session('role');
    return in_array($role, ['ADMIN', 'IPDS']);
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
    if (!$this->canView()) return redirect()->to('/');
    $model = new KegiatanModel();
    $optionModel = new OpsiKegiatanModel();
    $userModel = new \App\Models\UserModel();

    $data['kegiatan'] = $model->findAll();
    $opsi = $optionModel->findAll();
    $users = $userModel->findAll();

    $opsiMap = [];
    foreach ($opsi as $o) {
      $opsiMap[$o['id']] = $o['nama_kegiatan'];
    }

    $userMap = [];
    foreach ($users as $u) {
      $userMap[$u['id']] = $u['username'];
    }

    $data['opsiMap'] = $opsiMap;
    $data['userMap'] = $userMap;
    $data['canManage'] = $this->canManage();
    $data['canView'] = $this->canView();
    $data['currentUserId'] = session('user_id');
    $data['currentRole'] = session('role');
    $data['title'] = 'Kegiatan';
    $data['bulanList'] = $this->bulanList;
    return view('kegiatan/index', $data);
  }

  public function create()
  {
    if (!$this->canManageKegiatan()) return redirect()->to('/kegiatan');
    $optionModel = new OpsiKegiatanModel();
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
    if (!$this->canManageKegiatan()) return redirect()->to('/kegiatan');
    $validation = \Config\Services::validation();
    $validation->setRules([
      'id_opsi_kegiatan' => 'required',
      'tahun' => 'required|numeric',
      'bulan' => 'required',
      'tanggal_batas_cetak' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $optionModel = new OpsiKegiatanModel();
      $data['opsi'] = $optionModel->findAll();
      $data['validation'] = $validation;
      $data['bulanList'] = $this->bulanList;
      $data['blokSensusList'] = (new BlokSensusModel())->findAll();
      $data['slsList'] = (new SlsModel())->findAll();
      $data['desaList'] = (new DesaModel())->findAll();
      return view('kegiatan/create', $data);
    }

    // Validate user_id exists
    $userId = session('user_id');
    if (!$userId) {
      return redirect()->to('/login')->with('error', 'Session expired. Please login again.');
    }

    // Check if user exists in database
    $userModel = new \App\Models\UserModel();
    $user = $userModel->find($userId);
    if (!$user) {
      return redirect()->to('/login')->with('error', 'Invalid user session. Please login again.');
    }

    // Status default untuk semua kegiatan baru adalah "disiapkan (IPDS)"
    $status = 'disiapkan (IPDS)';
    $model = new KegiatanModel();
    $uuid = Uuid::uuid4()->toString();
    $model->insert([
      'id' => $uuid,
      'id_opsi_kegiatan' => $this->request->getPost('id_opsi_kegiatan'),
      'id_user' => $userId,
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
      $bsModel->insert([
        'id' => Uuid::uuid4()->toString(),
        'id_kegiatan' => $uuid,
        'id_blok_sensus' => $bs
      ]);
    }
    foreach (array_unique($this->request->getPost('sls') ?? []) as $sls) {
      $slsModel->insert([
        'id' => Uuid::uuid4()->toString(),
        'id_kegiatan' => $uuid,
        'id_sls' => $sls
      ]);
    }
    foreach (array_unique($this->request->getPost('desa') ?? []) as $desa) {
      $desaModel->insert([
        'id' => Uuid::uuid4()->toString(),
        'id_kegiatan' => $uuid,
        'id_desa' => $desa
      ]);
    }
    return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil ditambahkan');
  }

  public function edit($uuid)
  {
    if (!$this->canManageKegiatan($uuid)) {
      session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
      return redirect()->to('/kegiatan');
    }
    $model = new KegiatanModel();
    $optionModel = new OpsiKegiatanModel();
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
    $data['canChangeStatus'] = $this->canChangeStatus($uuid);
    $data['blokSensusList'] = (new BlokSensusModel())->findAll();
    $data['slsList'] = (new SlsModel())->findAll();
    $data['desaList'] = (new DesaModel())->findAll();
    $data['selectedBlokSensus'] = array_column((new KegiatanBlokSensusModel())->where('id_kegiatan', $uuid)->findAll(), 'id_blok_sensus');
    $data['selectedSls'] = array_column((new KegiatanSlsModel())->where('id_kegiatan', $uuid)->findAll(), 'id_sls');
    $data['selectedDesa'] = array_column((new KegiatanDesaModel())->where('id_kegiatan', $uuid)->findAll(), 'id_desa');
    return view('kegiatan/edit', $data);
  }

  public function update($uuid)
  {
    if (!$this->canManageKegiatan($uuid)) {
      session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
      return redirect()->to('/kegiatan');
    }
    $validation = \Config\Services::validation();
    $validation->setRules([
      'id_opsi_kegiatan' => 'required',
      'tahun' => 'required|numeric',
      'bulan' => 'required',
      'tanggal_batas_cetak' => 'required',
      'status' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new KegiatanModel();
      $optionModel = new OpsiKegiatanModel();
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
      $data['selectedBlokSensus'] = array_column((new KegiatanBlokSensusModel())->where('id_kegiatan', $uuid)->findAll(), 'id_blok_sensus');
      $data['selectedSls'] = array_column((new KegiatanSlsModel())->where('id_kegiatan', $uuid)->findAll(), 'id_sls');
      $data['selectedDesa'] = array_column((new KegiatanDesaModel())->where('id_kegiatan', $uuid)->findAll(), 'id_desa');
      return view('kegiatan/edit', $data);
    }
    $model = new KegiatanModel();
    $model->update($uuid, [
      'id_opsi_kegiatan' => $this->request->getPost('id_opsi_kegiatan'),
      'tahun' => $this->request->getPost('tahun'),
      'bulan' => $this->request->getPost('bulan'),
      'tanggal_batas_cetak' => $this->request->getPost('tanggal_batas_cetak'),
      'status' => $this->request->getPost('status'),
    ]);
    // Update relasi wilkerstat
    $bsModel = new KegiatanBlokSensusModel();
    $slsModel = new KegiatanSlsModel();
    $desaModel = new KegiatanDesaModel();
    $bsModel->where('id_kegiatan', $uuid)->delete();
    $slsModel->where('id_kegiatan', $uuid)->delete();
    $desaModel->where('id_kegiatan', $uuid)->delete();
    foreach (array_unique($this->request->getPost('blok_sensus') ?? []) as $bs) {
      $bsModel->insert([
        'id' => Uuid::uuid4()->toString(),
        'id_kegiatan' => $uuid,
        'id_blok_sensus' => $bs
      ]);
    }
    foreach (array_unique($this->request->getPost('sls') ?? []) as $sls) {
      $slsModel->insert([
        'id' => Uuid::uuid4()->toString(),
        'id_kegiatan' => $uuid,
        'id_sls' => $sls
      ]);
    }
    foreach (array_unique($this->request->getPost('desa') ?? []) as $desa) {
      $desaModel->insert([
        'id' => Uuid::uuid4()->toString(),
        'id_kegiatan' => $uuid,
        'id_desa' => $desa
      ]);
    }
    return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil diupdate');
  }

  public function detail($uuid)
  {
    if (!$this->canView()) return redirect()->to('/');

    $kegiatanModel = new KegiatanModel();
    $opsiModel = new OpsiKegiatanModel();
    $kegiatanBlokModel = new KegiatanBlokSensusModel();
    $kegiatanSlsModel = new KegiatanSlsModel();
    $kegiatanDesaModel = new KegiatanDesaModel();
    $blokModel = new BlokSensusModel();
    $slsModel = new SlsModel();
    $desaModel = new DesaModel();

    $kegiatan = $kegiatanModel->find($uuid);
    if (!$kegiatan) {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $opsi = $opsiModel->find($kegiatan['id_opsi_kegiatan']);

    // Get related wilkerstat
    $kegiatanBloks = $kegiatanBlokModel->where('id_kegiatan', $uuid)->findAll();
    $kegiatanSls = $kegiatanSlsModel->where('id_kegiatan', $uuid)->findAll();
    $kegiatanDesas = $kegiatanDesaModel->where('id_kegiatan', $uuid)->findAll();

    $blokSensus = [];
    foreach ($kegiatanBloks as $kb) {
      $blok = $blokModel->find($kb['id_blok_sensus']);
      if ($blok) $blokSensus[] = $blok;
    }

    $sls = [];
    foreach ($kegiatanSls as $ks) {
      $slsData = $slsModel->find($ks['id_sls']);
      if ($slsData) $sls[] = $slsData;
    }

    $desa = [];
    foreach ($kegiatanDesas as $kd) {
      $desaData = $desaModel->find($kd['id_desa']);
      if ($desaData) $desa[] = $desaData;
    }

    $data = [
      'title' => 'Detail Kegiatan',
      'kegiatan' => $kegiatan,
      'opsi' => $opsi,
      'blokSensus' => $blokSensus,
      'sls' => $sls,
      'desa' => $desa,
      'bulanList' => $this->bulanList,
      'canManage' => $this->canManage()
    ];

    return view('kegiatan/detail', $data);
  }

  public function delete($uuid)
  {
    if (!$this->canManageKegiatan($uuid)) return redirect()->to('/kegiatan');
    $model = new KegiatanModel();
    $model->delete($uuid);
    return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil dihapus');
  }
}
