<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use Ramsey\Uuid\Uuid;

class AuthController extends BaseController
{
  public function login()
  {
    return view('auth/login');
  }

  public function loginPost()
  {
    $session = session();
    $model = new UserModel();
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    $user = $model->where('username', $username)->first();

    if ($user && password_verify($password, $user['password'])) {
      $session->set([
        'user_id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'logged_in' => true
      ]);
      return redirect()->to('/');
    } else {
      $session->setFlashdata('error', 'Username atau password salah');
      return redirect()->to('/login');
    }
  }

  public function logout()
  {
    session()->destroy();
    return redirect()->to('/login');
  }
}
