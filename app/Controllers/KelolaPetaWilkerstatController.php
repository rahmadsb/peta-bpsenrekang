<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KegiatanWilkerstatPetaModel;
use App\Models\KegiatanModel;
use App\Models\BlokSensusModel;
use App\Models\SlsModel;
use App\Models\DesaModel;
use App\Models\KegiatanOptionModel;
use App\Models\KegiatanBlokSensusModel;
use App\Models\KegiatanSlsModel;
use App\Models\KegiatanDesaModel;

class KelolaPetaWilkerstatController extends BaseController
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

  public function index($kegiatan_uuid)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $kegiatanModel = new KegiatanModel();
    $kegiatan = $kegiatanModel->find($kegiatan_uuid);
    if (!$kegiatan) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    // Ambil wilkerstat terkait kegiatan (blok sensus, sls, desa)
    $blokModel = new BlokSensusModel();
    $slsModel = new SlsModel();
    $desaModel = new DesaModel();
    $blokPivot = (new KegiatanBlokSensusModel())->where('kegiatan_uuid', $kegiatan_uuid)->findAll();
    $slsPivot = (new KegiatanSlsModel())->where('kegiatan_uuid', $kegiatan_uuid)->findAll();
    $desaPivot = (new KegiatanDesaModel())->where('kegiatan_uuid', $kegiatan_uuid)->findAll();
    $blokSensus = [];
    foreach ($blokPivot as $bp) {
      $bs = $blokModel->find($bp['blok_sensus_uuid']);
      if ($bs) $blokSensus[] = $bs;
    }
    $sls = [];
    foreach ($slsPivot as $sp) {
      $s = $slsModel->find($sp['sls_uuid']);
      if ($s) $sls[] = $s;
    }
    $desa = [];
    foreach ($desaPivot as $dp) {
      $d = $desaModel->find($dp['desa_uuid']);
      if ($d) $desa[] = $d;
    }
    $wilkerstat = [
      'blok_sensus' => $blokSensus,
      'sls' => $sls,
      'desa' => $desa,
    ];
    $petaModel = new KegiatanWilkerstatPetaModel();
    // ambil data opsi kegiatan dari kegiatan yang dipilih
    $kegiatanOptionModel = new KegiatanOptionModel();
    $opsiKegiatan = $kegiatanOptionModel->find($kegiatan['kode_kegiatan_option']);
    $kegiatan['nama_kegiatan'] = $opsiKegiatan['nama_kegiatan'] . ' ' . $kegiatan['tahun'] . ' ' . $kegiatan['bulan'];
    $data = [
      'kegiatan' => $kegiatan,
      'wilkerstat' => $wilkerstat,
      'petaModel' => $petaModel,
      'title' => 'Kelola Peta Wilkerstat',
    ];
    return view('kelola_peta_wilkerstat/index', $data);
  }

  public function upload()
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $request = $this->request;
    $kegiatan_uuid = $request->getPost('kegiatan_uuid');
    $wilkerstat_type = $request->getPost('wilkerstat_type');
    $wilkerstat_uuid = $request->getPost('wilkerstat_uuid');
    $jenis_peta = $request->getPost('jenis_peta');
    $is_inset = $request->getPost('is_inset') ? 1 : 0;
    $files = $this->request->getFileMultiple('peta_files');
    $model = new \App\Models\KegiatanWilkerstatPetaModel();
    $allowed = ['image/jpeg', 'image/png'];
    $success = 0;
    $fail = 0;
    foreach ($files as $file) {
      if ($file->isValid() && in_array($file->getMimeType(), $allowed)) {
        $newName = uniqid() . '.' . $file->getExtension();
        $file->move(WRITEPATH . 'uploads', $newName);
        $model->insert([
          'kegiatan_uuid' => $kegiatan_uuid,
          'wilkerstat_type' => $wilkerstat_type,
          'wilkerstat_uuid' => $wilkerstat_uuid,
          'jenis_peta' => $jenis_peta,
          'is_inset' => $is_inset,
          'file_path' => $newName,
          'nama_file' => $file->getClientName(),
          'uploaded_at' => date('Y-m-d H:i:s'),
          'uploader' => session('username'),
        ]);
        $success++;
      } else {
        $fail++;
      }
    }
    if ($success > 0) {
      return redirect()->back()->with('success', "$success file berhasil diupload. $fail gagal.");
    } else {
      return redirect()->back()->with('error', 'Upload gagal. Pastikan file JPG/PNG.');
    }
  }

  public function download($id)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $petaModel = new KegiatanWilkerstatPetaModel();
    $file = $petaModel->find($id);
    if (!$file) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    return $this->response->download(WRITEPATH . 'uploads/' . $file['file_path'], null);
  }

  public function delete($id)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $petaModel = new KegiatanWilkerstatPetaModel();
    $file = $petaModel->find($id);
    if ($file && file_exists(WRITEPATH . 'uploads/' . $file['file_path'])) {
      unlink(WRITEPATH . 'uploads/' . $file['file_path']);
    }
    $petaModel->delete($id);
    return redirect()->back()->with('success', 'File peta berhasil dihapus');
  }
}
