<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KegiatanModel;
use App\Models\KegiatanOptionModel;
use Ramsey\Uuid\Uuid;

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
    return view('kegiatan/create', $data);
  }

  public function store()
  {
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
      return view('kegiatan/create', $data);
    }
    $role = session('role');
    $status = 'disiapkan (IPDS)';
    if ($role === 'SUBJECT_MATTER') $status = 'digunakan (SM)';
    $model = new KegiatanModel();
    $model->insert([
      'uuid' => Uuid::uuid4()->toString(),
      'kode_kegiatan_option' => $this->request->getPost('kode_kegiatan_option'),
      'id_user' => session('user_id'),
      'tahun' => $this->request->getPost('tahun'),
      'bulan' => $this->request->getPost('bulan'),
      'tanggal_batas_cetak' => $this->request->getPost('tanggal_batas_cetak'),
      'status' => $status,
    ]);
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
