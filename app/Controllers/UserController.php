<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Ramsey\Uuid\Uuid;

class UserController extends BaseController
{
  private function checkAccess()
  {
    if (!session()->get('logged_in')) {
      return redirect()->to('/login');
    }
    $role = session('role');
    if ($role !== 'ADMIN') {
      return redirect()->to('/')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengakses manajemen user.');
    }
    return null;
  }

  public function index()
  {
    if ($redirect = $this->checkAccess()) return $redirect;

    $model = new UserModel();
    $data['users'] = $model->select('id, username, role')->findAll();
    $data['title'] = 'Manajemen User';
    return view('user/index', $data);
  }

  public function create()
  {
    if ($redirect = $this->checkAccess()) return $redirect;

    $data['title'] = 'Tambah User';
    return view('user/create', $data);
  }

  public function store()
  {
    if ($redirect = $this->checkAccess()) return $redirect;

    $validation =  \Config\Services::validation();
    $validation->setRules([
      'username' => 'required|min_length[3]',
      'password' => 'required|min_length[6]',
      'role'     => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      return view('user/create', [
        'validation' => $validation
      ]);
    }
    $model = new UserModel();
    $model->insert([
      'id' => Uuid::uuid4()->toString(),
      'username' => $this->request->getPost('username'),
      'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
      'role' => $this->request->getPost('role'),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);
    return redirect()->to('/user')->with('success', 'User berhasil ditambahkan');
  }

  public function edit($id)
  {
    if ($redirect = $this->checkAccess()) return $redirect;

    $model = new UserModel();
    $user = $model->find($id);
    if (!$user) {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    $data['title'] = 'Edit User';
    $data['user'] = $user;
    return view('user/edit', $data);
  }

  public function update($id)
  {
    if ($redirect = $this->checkAccess()) return $redirect;

    $validation =  \Config\Services::validation();
    $validation->setRules([
      'username' => 'required|min_length[3]',
      'password' => 'permit_empty|min_length[6]',
      'role'     => 'required',
    ]);
    if (!$validation->withRequest($this->request)->run()) {
      $model = new UserModel();
      $user = $model->find($id);
      return view('user/edit', [
        'user' => $user,
        'validation' => $validation
      ]);
    }
    $model = new UserModel();
    $data = [
      'username' => $this->request->getPost('username'),
      'role' => $this->request->getPost('role'),
      'updated_at' => date('Y-m-d H:i:s'),
    ];
    $password = $this->request->getPost('password');
    if ($password) {
      $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
    $model->update($id, $data);
    return redirect()->to('/user')->with('success', 'User berhasil diupdate');
  }

  public function delete($id)
  {
    if ($redirect = $this->checkAccess()) return $redirect;

    $model = new UserModel();
    $model->delete($id);
    return redirect()->to('/user')->with('success', 'User berhasil dihapus');
  }
}
