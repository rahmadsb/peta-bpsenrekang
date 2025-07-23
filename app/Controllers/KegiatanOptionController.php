<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KegiatanOptionModel;
use Ramsey\Uuid\Uuid;

class KegiatanOptionController extends BaseController
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
    $model = new KegiatanOptionModel();
    $data['opsi'] = $model->findAll();
    $data['title'] = 'Opsi Kegiatan';
    return view('kegiatan_option/index', $data);
  }

  public function create()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $data['title'] = 'Tambah Opsi Kegiatan';
    return view('kegiatan_option/create', $data);
  }

  public function store()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_kegiatan' => 'required|is_unique[kegiatan_option.kode_kegiatan]',
      'nama_kegiatan' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      return view('kegiatan_option/create', [
        'validation' => $validation
      ]);
    }
    $model = new KegiatanOptionModel();
    $model->insert([
      'uuid' => Uuid::uuid4()->toString(),
      'kode_kegiatan' => $this->request->getPost('kode_kegiatan'),
      'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
    ]);
    return redirect()->to('/kegiatan-option')->with('success', 'Opsi kegiatan berhasil ditambahkan');
  }

  public function edit($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new KegiatanOptionModel();
    $opsi = $model->find($uuid);
    if (!$opsi) {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    $data['title'] = 'Edit Opsi Kegiatan';
    $data['opsi'] = $opsi;
    return view('kegiatan_option/edit', $data);
  }

  public function update($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_kegiatan' => 'required|is_unique[kegiatan_option.kode_kegiatan,uuid,' . $uuid . ']',
      'nama_kegiatan' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new KegiatanOptionModel();
      $opsi = $model->find($uuid);
      return view('kegiatan_option/edit', [
        'opsi' => $opsi,
        'validation' => $validation
      ]);
    }
    $model = new KegiatanOptionModel();
    $model->update($uuid, [
      'kode_kegiatan' => $this->request->getPost('kode_kegiatan'),
      'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
    ]);
    return redirect()->to('/kegiatan-option')->with('success', 'Opsi kegiatan berhasil diupdate');
  }

  public function delete($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new KegiatanOptionModel();
    $model->delete($uuid);
    return redirect()->to('/kegiatan-option')->with('success', 'Opsi kegiatan berhasil dihapus');
  }
}
