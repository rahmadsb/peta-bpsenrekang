<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
  public function admin()
  {
    $kegiatanModel = new \App\Models\KegiatanModel();
    $petaModel = new \App\Models\KegiatanWilkerstatPetaModel();
    $userModel = new \App\Models\UserModel();
    $blokModel = new \App\Models\BlokSensusModel();
    $slsModel = new \App\Models\SlsModel();
    $desaModel = new \App\Models\DesaModel();

    $totalKegiatan = $kegiatanModel->countAllResults();
    $totalPeta = $petaModel->countAllResults();
    $totalUser = $userModel->countAllResults();
    $totalBlok = $blokModel->countAllResults();
    $totalSls = $slsModel->countAllResults();
    $totalDesa = $desaModel->countAllResults();

    $statusList = ['disiapkan (IPDS)', 'digunakan (SM)', 'scan peta (IPDS)', 'upload peta (IPDS)', 'selesai'];
    $kegiatanPerStatus = [];
    foreach ($statusList as $status) {
      $kegiatanPerStatus[$status] = $kegiatanModel->where('status', $status)->countAllResults();
    }

    $data = [
      'title' => 'Dashboard Admin',
      'totalKegiatan' => $totalKegiatan,
      'totalPeta' => $totalPeta,
      'totalUser' => $totalUser,
      'totalBlok' => $totalBlok,
      'totalSls' => $totalSls,
      'totalDesa' => $totalDesa,
      'kegiatanPerStatus' => $kegiatanPerStatus,
      'statusList' => $statusList,
    ];
    return view('dashboard/admin', $data);
  }

  public function ipds()
  {
    $kegiatanModel = new \App\Models\KegiatanModel();
    $petaModel = new \App\Models\KegiatanWilkerstatPetaModel();
    $blokModel = new \App\Models\BlokSensusModel();
    $slsModel = new \App\Models\SlsModel();
    $desaModel = new \App\Models\DesaModel();

    $totalKegiatan = $kegiatanModel->countAllResults();
    $totalPeta = $petaModel->countAllResults();
    $totalBlok = $blokModel->countAllResults();
    $totalSls = $slsModel->countAllResults();
    $totalDesa = $desaModel->countAllResults();

    $statusList = ['disiapkan (IPDS)', 'digunakan (SM)', 'scan peta (IPDS)', 'upload peta (IPDS)', 'selesai'];
    $kegiatanPerStatus = [];
    foreach ($statusList as $status) {
      $kegiatanPerStatus[$status] = $kegiatanModel->where('status', $status)->countAllResults();
    }

    $data = [
      'title' => 'Dashboard IPDS',
      'totalKegiatan' => $totalKegiatan,
      'totalPeta' => $totalPeta,
      'totalBlok' => $totalBlok,
      'totalSls' => $totalSls,
      'totalDesa' => $totalDesa,
      'kegiatanPerStatus' => $kegiatanPerStatus,
      'statusList' => $statusList,
    ];
    return view('dashboard/ipds', $data);
  }

  public function subjectMatter()
  {
    $kegiatanModel = new \App\Models\KegiatanModel();
    $petaModel = new \App\Models\KegiatanWilkerstatPetaModel();
    $blokModel = new \App\Models\BlokSensusModel();
    $slsModel = new \App\Models\SlsModel();
    $desaModel = new \App\Models\DesaModel();

    $totalKegiatan = $kegiatanModel->countAllResults();
    $totalPeta = $petaModel->countAllResults();
    $totalBlok = $blokModel->countAllResults();
    $totalSls = $slsModel->countAllResults();
    $totalDesa = $desaModel->countAllResults();

    $statusList = ['disiapkan (IPDS)', 'digunakan (SM)', 'scan peta (IPDS)', 'upload peta (IPDS)', 'selesai'];
    $kegiatanPerStatus = [];
    foreach ($statusList as $status) {
      $kegiatanPerStatus[$status] = $kegiatanModel->where('status', $status)->countAllResults();
    }

    $data = [
      'title' => 'Dashboard Subject Matter',
      'totalKegiatan' => $totalKegiatan,
      'totalPeta' => $totalPeta,
      'totalBlok' => $totalBlok,
      'totalSls' => $totalSls,
      'totalDesa' => $totalDesa,
      'kegiatanPerStatus' => $kegiatanPerStatus,
      'statusList' => $statusList,
    ];
    return view('dashboard/subject_matter', $data);
  }

  public function guest()
  {
    $kegiatanModel = new \App\Models\KegiatanModel();
    $petaModel = new \App\Models\KegiatanWilkerstatPetaModel();
    $blokModel = new \App\Models\BlokSensusModel();
    $slsModel = new \App\Models\SlsModel();
    $desaModel = new \App\Models\DesaModel();

    $totalKegiatan = $kegiatanModel->countAllResults();
    $totalPeta = $petaModel->countAllResults();
    $totalBlok = $blokModel->countAllResults();
    $totalSls = $slsModel->countAllResults();
    $totalDesa = $desaModel->countAllResults();

    $statusList = ['disiapkan (IPDS)', 'digunakan (SM)', 'scan peta (IPDS)', 'upload peta (IPDS)', 'selesai'];
    $kegiatanPerStatus = [];
    foreach ($statusList as $status) {
      $kegiatanPerStatus[$status] = $kegiatanModel->where('status', $status)->countAllResults();
    }

    $data = [
      'title' => 'Dashboard Guest',
      'totalKegiatan' => $totalKegiatan,
      'totalPeta' => $totalPeta,
      'totalBlok' => $totalBlok,
      'totalSls' => $totalSls,
      'totalDesa' => $totalDesa,
      'kegiatanPerStatus' => $kegiatanPerStatus,
      'statusList' => $statusList,
    ];
    return view('dashboard/guest', $data);
  }

  public function index()
  {
    $role = session('role');
    if ($role === 'ADMIN') {
      return redirect()->to('/admin');
    } elseif ($role === 'IPDS') {
      return redirect()->to('/ipds');
    } elseif ($role === 'SUBJECT_MATTER') {
      return redirect()->to('/subject-matter');
    } elseif ($role === 'GUEST') {
      return redirect()->to('/guest');
    } else {
      return redirect()->to('/admin');
    }
  }
}
