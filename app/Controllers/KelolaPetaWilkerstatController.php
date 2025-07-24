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
    $parent_peta_id = $request->getPost('parent_peta_id');
    $files = $this->request->getFileMultiple('peta_files');
    $model = new \App\Models\KegiatanWilkerstatPetaModel();
    $allowed = ['image/jpeg', 'image/png'];
    $success = 0;
    $fail = 0;
    foreach ($files as $file) {
      if ($file->isValid() && in_array($file->getMimeType(), $allowed)) {
        $newName = uniqid() . '.' . $file->getExtension();
        $file->move(WRITEPATH . 'uploads', $newName);
        $insertData = [
          'kegiatan_uuid' => $kegiatan_uuid,
          'wilkerstat_type' => $wilkerstat_type,
          'wilkerstat_uuid' => $wilkerstat_uuid,
          'jenis_peta' => $jenis_peta,
          'file_path' => $newName,
          'nama_file' => $file->getClientName(),
          'uploaded_at' => date('Y-m-d H:i:s'),
          'uploader' => session('username'),
        ];
        if ($parent_peta_id) {
          $insertData['parent_peta_id'] = $parent_peta_id;
        }
        $model->insert($insertData);
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
    if (!$file) return redirect()->back()->with('error', 'File tidak ditemukan.');
    // Jika peta utama, hapus semua inset-nya juga
    if (empty($file['parent_peta_id'])) {
      $insets = $petaModel->where('parent_peta_id', $id)->findAll();
      foreach ($insets as $inset) {
        if ($inset['file_path'] && file_exists(WRITEPATH . 'uploads/' . $inset['file_path'])) {
          unlink(WRITEPATH . 'uploads/' . $inset['file_path']);
        }
        $petaModel->delete($inset['id']);
      }
    }
    // Hapus file utama/inset
    if ($file['file_path'] && file_exists(WRITEPATH . 'uploads/' . $file['file_path'])) {
      unlink(WRITEPATH . 'uploads/' . $file['file_path']);
    }
    $petaModel->delete($id);
    return redirect()->back()->with('success', 'File peta berhasil dihapus.');
  }

  public function replace($id)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $petaModel = new KegiatanWilkerstatPetaModel();
    $file = $petaModel->find($id);
    if (!$file) return redirect()->back()->with('error', 'File tidak ditemukan.');
    $newFile = $this->request->getFile('replace_file');
    $allowed = ['image/jpeg', 'image/png'];
    if (!$newFile->isValid() || !in_array($newFile->getMimeType(), $allowed)) {
      return redirect()->back()->with('error', 'File tidak valid. Hanya JPG/PNG yang diizinkan.');
    }
    // Hapus file lama
    if ($file['file_path'] && file_exists(WRITEPATH . 'uploads/' . $file['file_path'])) {
      unlink(WRITEPATH . 'uploads/' . $file['file_path']);
    }
    // Upload file baru
    $newName = uniqid() . '.' . $newFile->getExtension();
    $newFile->move(WRITEPATH . 'uploads', $newName);
    // Update database
    $petaModel->update($id, [
      'file_path' => $newName,
      'nama_file' => $newFile->getClientName(),
      'uploaded_at' => date('Y-m-d H:i:s'),
      'uploader' => session('username'),
    ]);
    return redirect()->back()->with('success', 'File berhasil diganti.');
  }

  public function rename($id)
  {
    if ($redirect = $this->checkAccess()) return $redirect;
    $petaModel = new KegiatanWilkerstatPetaModel();
    $file = $petaModel->find($id);
    if (!$file) return redirect()->back()->with('error', 'File tidak ditemukan.');
    $newNama = $this->request->getPost('new_nama_file');
    if (!$newNama || trim($newNama) === '') {
      return redirect()->back()->with('error', 'Nama file tidak boleh kosong.');
    }
    // Rename file fisik di server
    $oldPath = WRITEPATH . 'uploads/' . $file['file_path'];
    $ext = pathinfo($file['file_path'], PATHINFO_EXTENSION);
    $newNamaServer = pathinfo($newNama, PATHINFO_FILENAME) . '.' . $ext;
    $newPath = WRITEPATH . 'uploads/' . $newNamaServer;
    if (file_exists($oldPath)) {
      if (!rename($oldPath, $newPath)) {
        return redirect()->back()->with('error', 'Gagal mengganti nama file di server.');
      }
    }
    $petaModel->update($id, ['nama_file' => $newNama, 'file_path' => $newNamaServer]);
    return redirect()->back()->with('success', 'Nama file berhasil diubah.');
  }
}
