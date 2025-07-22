<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
  public function admin()
  {
    $data = ['title' => 'Dashboard Admin'];
    return view('dashboard/admin', $data);
  }

  public function ipds()
  {
    $data = [
      'title' => 'Dashboard IPDS',
    ];
    return view('dashboard/ipds', $data);
  }
}
