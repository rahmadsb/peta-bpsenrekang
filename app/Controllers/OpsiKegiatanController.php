<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OpsiKegiatanModel;
use Ramsey\Uuid\Uuid;

class OpsiKegiatanController extends BaseController
{
  private function checkAccess()
  {
    if (!session()->get('logged_in')) {
      return redirect()->to('/login');
    }
    $role = session('role');
    if ($role !== 'ADMIN') {
      session()->setFlashdata('error', 'Anda tidak memiliki akses untuk fitur ini.');
      return redirect()->to('/');
    }
    return null;
  }

  public function index()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new OpsiKegiatanModel();
    $data['opsi'] = $model->findAll();
    $data['title'] = 'Opsi Kegiatan';
    return view('opsi_kegiatan/index', $data);
  }

  public function create()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $data['title'] = 'Tambah Opsi Kegiatan';
    return view('opsi_kegiatan/create', $data);
  }

  public function store()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_kegiatan' => 'required|is_unique[opsi_kegiatan.kode_kegiatan]',
      'nama_kegiatan' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      return view('opsi_kegiatan/create', [
        'validation' => $validation
      ]);
    }
    $model = new OpsiKegiatanModel();
    $model->insert([
      'id' => Uuid::uuid4()->toString(),
      'kode_kegiatan' => $this->request->getPost('kode_kegiatan'),
      'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
    ]);
    return redirect()->to('/opsi-kegiatan')->with('success', 'Opsi kegiatan berhasil ditambahkan');
  }

  public function edit($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new OpsiKegiatanModel();
    $opsi = $model->find($uuid);
    if (!$opsi) {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    $data['title'] = 'Edit Opsi Kegiatan';
    $data['opsi'] = $opsi;
    return view('opsi_kegiatan/edit', $data);
  }

  public function update($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $validation = \Config\Services::validation();
    $validation->setRules([
      'kode_kegiatan' => 'required|is_unique[opsi_kegiatan.kode_kegiatan,id,' . $uuid . ']',
      'nama_kegiatan' => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new OpsiKegiatanModel();
      $opsi = $model->find($uuid);
      return view('opsi_kegiatan/edit', [
        'opsi' => $opsi,
        'validation' => $validation
      ]);
    }
    $model = new OpsiKegiatanModel();
    $model->update($uuid, [
      'kode_kegiatan' => $this->request->getPost('kode_kegiatan'),
      'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
    ]);
    return redirect()->to('/opsi-kegiatan')->with('success', 'Opsi kegiatan berhasil diupdate');
  }

  public function delete($uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $model = new OpsiKegiatanModel();
    $model->delete($uuid);
    return redirect()->to('/opsi-kegiatan')->with('success', 'Opsi kegiatan berhasil dihapus');
  }
}
