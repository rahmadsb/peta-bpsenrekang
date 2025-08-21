<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KegiatanWilkerstatPetaModel;
use App\Models\KegiatanModel;
use App\Models\BlokSensusModel;
use App\Models\SlsModel;
use App\Models\DesaModel;
use App\Models\OpsiKegiatanModel;
use App\Models\KegiatanBlokSensusModel;
use App\Models\KegiatanSlsModel;
use App\Models\KegiatanDesaModel;
use Ramsey\Uuid\Uuid;

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
    $blokPivot = (new KegiatanBlokSensusModel())->where('id_kegiatan', $kegiatan_uuid)->findAll();
    $slsPivot = (new KegiatanSlsModel())->where('id_kegiatan', $kegiatan_uuid)->findAll();
    $desaPivot = (new KegiatanDesaModel())->where('id_kegiatan', $kegiatan_uuid)->findAll();
    $blokSensus = [];
    foreach ($blokPivot as $bp) {
      $bs = $blokModel->find($bp['id_blok_sensus']);
      if ($bs) $blokSensus[] = $bs;
    }
    $sls = [];
    foreach ($slsPivot as $sp) {
      $s = $slsModel->find($sp['id_sls']);
      if ($s) $sls[] = $s;
    }
    $desa = [];
    foreach ($desaPivot as $dp) {
      $d = $desaModel->find($dp['id_desa']);
      if ($d) $desa[] = $d;
    }
    $wilkerstat = [
      'blok_sensus' => $blokSensus,
      'sls' => $sls,
      'desa' => $desa,
    ];
    $petaModel = new KegiatanWilkerstatPetaModel();
    // ambil data opsi kegiatan dari kegiatan yang dipilih
    $kegiatanOptionModel = new OpsiKegiatanModel();
    $opsiKegiatan = $kegiatanOptionModel->find($kegiatan['id_opsi_kegiatan']);
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
    $kegiatan_uuid = $request->getPost('id_kegiatan');
    $wilkerstat_type = $request->getPost('wilkerstat_type');
    $wilkerstat_uuid = $request->getPost('id_wilkerstat');
    $jenis_peta = $request->getPost('jenis_peta');
    $parent_peta_id = $request->getPost('id_parent_peta');
    $files = $this->request->getFileMultiple('peta_files');

    // Validate required data
    if (!$kegiatan_uuid) {
      return redirect()->back()->with('error', 'ID Kegiatan tidak valid.');
    }
    if (!$wilkerstat_uuid) {
      return redirect()->back()->with('error', 'ID Wilkerstat tidak valid.');
    }

    $model = new \App\Models\KegiatanWilkerstatPetaModel();

    // Jika ini upload peta utama (tanpa parent_peta_id), cek apakah sudah ada peta utama
    if (!$parent_peta_id) {
      $existingPetaUtama = $model->where([
        'id_kegiatan' => $kegiatan_uuid,
        'wilkerstat_type' => $wilkerstat_type,
        'id_wilkerstat' => $wilkerstat_uuid,
        'jenis_peta' => $jenis_peta,
        'id_parent_peta' => null
      ])->first();

      if ($existingPetaUtama) {
        return redirect()->back()->with('error', 'Peta utama untuk jenis peta ini sudah ada. Gunakan fitur ganti file untuk mengubah peta utama.');
      }

      // Validasi hanya boleh upload satu file untuk peta utama
      if (count($files) > 1) {
        return redirect()->back()->with('error', 'Peta utama hanya boleh terdiri dari satu file saja.');
      }
    }

    $allowed = ['image/jpeg', 'image/png'];
    $success = 0;
    $fail = 0;
    foreach ($files as $file) {
      if ($file->isValid() && in_array($file->getMimeType(), $allowed)) {
        $newName = uniqid() . '.' . $file->getExtension();
        $file->move(WRITEPATH . 'uploads', $newName);
        $insertData = [
          'id' => Uuid::uuid4()->toString(),
          'id_kegiatan' => $kegiatan_uuid,
          'wilkerstat_type' => $wilkerstat_type,
          'id_wilkerstat' => $wilkerstat_uuid,
          'jenis_peta' => $jenis_peta,
          'file_path' => $newName,
          'nama_file' => $file->getClientName(),
          'uploaded_at' => date('Y-m-d H:i:s'),
          'uploader' => session('username'),
        ];
        if ($parent_peta_id) {
          $insertData['id_parent_peta'] = $parent_peta_id;
        }
        $model->insert($insertData);
        $success++;
      } else {
        $fail++;
      }
    }

    if ($success > 0) {
      $message = $parent_peta_id ?
        "$success file inset berhasil diupload. $fail gagal." :
        "Peta utama berhasil diupload.";
      return redirect()->back()->with('success', $message);
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
    if (empty($file['id_parent_peta'])) {
      $insets = $petaModel->where('id_parent_peta', $id)->findAll();
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

    // Ambil ekstensi file asli dan pastikan tidak berubah
    $originalExt = pathinfo($file['nama_file'], PATHINFO_EXTENSION);
    $newNamaFull = trim($newNama) . '.' . $originalExt;

    // Validate nama file (tidak boleh mengandung karakter khusus)
    if (!preg_match('/^[a-zA-Z0-9\s\-_\(\)]+$/', $newNama)) {
      return redirect()->back()->with('error', 'Nama file hanya boleh mengandung huruf, angka, spasi, tanda minus, underscore, dan tanda kurung.');
    }

    // Update nama file di database (tidak perlu mengubah file fisik di server)
    $petaModel->update($id, [
      'nama_file' => $newNamaFull,
      'uploaded_at' => date('Y-m-d H:i:s'),
      'uploader' => session('username')
    ]);
    return redirect()->back()->with('success', 'Nama file berhasil diubah.');
  }

  public function downloadAllPeta($kegiatan_uuid)
  {
    // Add logging for debugging
    log_message('info', 'Starting downloadAllPeta for kegiatan: ' . $kegiatan_uuid);

    // Check access permission
    if (!session()->get('logged_in')) {
      log_message('error', 'User not logged in');
      return redirect()->to('/login');
    }

    $kegiatanModel = new KegiatanModel();
    $kegiatan = $kegiatanModel->find($kegiatan_uuid);
    if (!$kegiatan) {
      log_message('error', 'Kegiatan not found: ' . $kegiatan_uuid);
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // Check if user can access this kegiatan's files
    $role = session('role');
    $userId = session('user_id');
    log_message('info', 'User role: ' . $role . ', User ID: ' . $userId);

    if (!in_array($role, ['ADMIN', 'IPDS', 'SUBJECT_MATTER'])) {
      log_message('error', 'User does not have permission to download');
      return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk download peta.');
    }

    // Subject Matter can only download peta from their own kegiatan
    if ($role === 'SUBJECT_MATTER' && $kegiatan['id_user'] !== $userId) {
      log_message('error', 'Subject matter trying to access other user kegiatan');
      return redirect()->back()->with('error', 'Anda hanya dapat download peta dari kegiatan yang Anda buat.');
    }

    // Get all wilkerstat related to this kegiatan
    $blokPivot = (new KegiatanBlokSensusModel())->where('id_kegiatan', $kegiatan_uuid)->findAll();
    $slsPivot = (new KegiatanSlsModel())->where('id_kegiatan', $kegiatan_uuid)->findAll();
    $desaPivot = (new KegiatanDesaModel())->where('id_kegiatan', $kegiatan_uuid)->findAll();

    log_message('info', 'Found ' . count($blokPivot) . ' blok sensus, ' . count($slsPivot) . ' SLS, ' . count($desaPivot) . ' desa');

    $petaModel = new KegiatanWilkerstatPetaModel();
    $allFiles = [];

    // Get blok sensus peta files - ambil dari kegiatan terbaru saja
    foreach ($blokPivot as $bp) {
      log_message('debug', 'Looking for blok sensus peta with id_wilkerstat: ' . $bp['id_blok_sensus']);

      // Cari kegiatan terbaru yang memiliki peta untuk wilkerstat ini
      $latestKegiatanWithPeta = $petaModel->select('kegiatan_wilkerstat_peta.*, kegiatan.created_at as kegiatan_created_at')
        ->join('kegiatan', 'kegiatan.id = kegiatan_wilkerstat_peta.id_kegiatan')
        ->where([
          'kegiatan_wilkerstat_peta.wilkerstat_type' => 'blok_sensus',
          'kegiatan_wilkerstat_peta.id_wilkerstat' => $bp['id_blok_sensus']
        ])
        ->orderBy('kegiatan.created_at', 'DESC')
        ->first();

      if ($latestKegiatanWithPeta) {
        // Ambil semua peta dari kegiatan terbaru untuk wilkerstat ini
        $files = $petaModel->where([
          'wilkerstat_type' => 'blok_sensus',
          'id_wilkerstat' => $bp['id_blok_sensus'],
          'id_kegiatan' => $latestKegiatanWithPeta['id_kegiatan']
        ])->findAll();

        log_message('debug', 'Found ' . count($files) . ' files for blok sensus ' . $bp['id_blok_sensus'] . ' from latest kegiatan: ' . $latestKegiatanWithPeta['id_kegiatan']);
        $allFiles = array_merge($allFiles, $files);
      } else {
        log_message('debug', 'No peta found for blok sensus ' . $bp['id_blok_sensus']);
      }
    }

    // Get SLS peta files - ambil dari kegiatan terbaru saja
    foreach ($slsPivot as $sp) {
      log_message('debug', 'Looking for SLS peta with id_wilkerstat: ' . $sp['id_sls']);

      // Cari kegiatan terbaru yang memiliki peta untuk wilkerstat ini
      $latestKegiatanWithPeta = $petaModel->select('kegiatan_wilkerstat_peta.*, kegiatan.created_at as kegiatan_created_at')
        ->join('kegiatan', 'kegiatan.id = kegiatan_wilkerstat_peta.id_kegiatan')
        ->where([
          'kegiatan_wilkerstat_peta.wilkerstat_type' => 'sls',
          'kegiatan_wilkerstat_peta.id_wilkerstat' => $sp['id_sls']
        ])
        ->orderBy('kegiatan.created_at', 'DESC')
        ->first();

      if ($latestKegiatanWithPeta) {
        // Ambil semua peta dari kegiatan terbaru untuk wilkerstat ini
        $files = $petaModel->where([
          'wilkerstat_type' => 'sls',
          'id_wilkerstat' => $sp['id_sls'],
          'id_kegiatan' => $latestKegiatanWithPeta['id_kegiatan']
        ])->findAll();

        log_message('debug', 'Found ' . count($files) . ' files for SLS ' . $sp['id_sls'] . ' from latest kegiatan: ' . $latestKegiatanWithPeta['id_kegiatan']);
        $allFiles = array_merge($allFiles, $files);
      } else {
        log_message('debug', 'No peta found for SLS ' . $sp['id_sls']);
      }
    }

    // Get Desa peta files - ambil dari kegiatan terbaru saja
    foreach ($desaPivot as $dp) {
      log_message('debug', 'Looking for desa peta with id_wilkerstat: ' . $dp['id_desa']);

      // Cari kegiatan terbaru yang memiliki peta untuk wilkerstat ini
      $latestKegiatanWithPeta = $petaModel->select('kegiatan_wilkerstat_peta.*, kegiatan.created_at as kegiatan_created_at')
        ->join('kegiatan', 'kegiatan.id = kegiatan_wilkerstat_peta.id_kegiatan')
        ->where([
          'kegiatan_wilkerstat_peta.wilkerstat_type' => 'desa',
          'kegiatan_wilkerstat_peta.id_wilkerstat' => $dp['id_desa']
        ])
        ->orderBy('kegiatan.created_at', 'DESC')
        ->first();

      if ($latestKegiatanWithPeta) {
        // Ambil semua peta dari kegiatan terbaru untuk wilkerstat ini
        $files = $petaModel->where([
          'wilkerstat_type' => 'desa',
          'id_wilkerstat' => $dp['id_desa'],
          'id_kegiatan' => $latestKegiatanWithPeta['id_kegiatan']
        ])->findAll();

        log_message('debug', 'Found ' . count($files) . ' files for desa ' . $dp['id_desa'] . ' from latest kegiatan: ' . $latestKegiatanWithPeta['id_kegiatan']);
        $allFiles = array_merge($allFiles, $files);
      } else {
        log_message('debug', 'No peta found for desa ' . $dp['id_desa']);
      }
    }

    log_message('info', 'Found total ' . count($allFiles) . ' files to download');

    if (empty($allFiles)) {
      log_message('error', 'No files found for download');
      return redirect()->back()->with('error', 'Tidak ada file peta yang tersedia untuk didownload.');
    }

    // Check if ZipArchive extension is available
    if (!extension_loaded('zip')) {
      log_message('error', 'ZipArchive extension not loaded');
      return redirect()->back()->with('error', 'Server tidak mendukung ZipArchive extension.');
    }

    // Create zip file
    $zip = new \ZipArchive();
    $opsiModel = new OpsiKegiatanModel();
    $opsi = $opsiModel->find($kegiatan['id_opsi_kegiatan']);
    $zipFileName = 'Peta_' . ($opsi['nama_kegiatan'] ?? 'Kegiatan') . '_' . $kegiatan['tahun'] . '_' . $kegiatan['bulan'] . '.zip';
    $zipFilePath = WRITEPATH . 'uploads/temp/' . $zipFileName;

    log_message('info', 'Creating zip file: ' . $zipFilePath);

    // Create temp directory if not exists
    if (!is_dir(WRITEPATH . 'uploads/temp/')) {
      $created = mkdir(WRITEPATH . 'uploads/temp/', 0755, true);
      log_message('info', 'Creating temp directory: ' . ($created ? 'success' : 'failed'));
    }

    $zipResult = $zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    if ($zipResult === TRUE) {
      log_message('info', 'Zip file opened successfully');

      // Group files by wilkerstat type and ID for better organization
      $groupedFiles = [];
      $kegiatanInfoMap = []; // Cache untuk informasi kegiatan

      foreach ($allFiles as $file) {
        // Get kegiatan info for better folder naming
        if (!isset($kegiatanInfoMap[$file['id_kegiatan']])) {
          $kegiatanInfo = $kegiatanModel->find($file['id_kegiatan']);
          $opsiInfo = $opsiModel->find($kegiatanInfo['id_opsi_kegiatan']);
          $kegiatanInfoMap[$file['id_kegiatan']] = [
            'nama' => $opsiInfo['nama_kegiatan'] ?? 'Kegiatan',
            'tahun' => $kegiatanInfo['tahun'],
            'bulan' => $kegiatanInfo['bulan']
          ];
        }

        $kegiatanName = $kegiatanInfoMap[$file['id_kegiatan']]['nama'] . '_' .
          $kegiatanInfoMap[$file['id_kegiatan']]['tahun'] . '_' .
          $kegiatanInfoMap[$file['id_kegiatan']]['bulan'];

        $key = $kegiatanName . '/' . $file['wilkerstat_type'] . '_' . $file['id_wilkerstat'] . '_' . $file['jenis_peta'];
        if (!isset($groupedFiles[$key])) {
          $groupedFiles[$key] = [];
        }
        $groupedFiles[$key][] = $file;
      }

      log_message('info', 'Grouped files into ' . count($groupedFiles) . ' groups');

      // Add files to zip with organized folder structure
      $addedFiles = 0;
      foreach ($groupedFiles as $groupKey => $files) {
        foreach ($files as $file) {
          $filePath = WRITEPATH . 'uploads/' . $file['file_path'];
          if (file_exists($filePath)) {
            // Create folder structure: wilkerstat_type/id_wilkerstat/jenis_peta/
            $folderName = str_replace('_', '/', $groupKey);
            $zipEntryName = $folderName . '/' . $file['nama_file'];
            $zip->addFile($filePath, $zipEntryName);
            $addedFiles++;
            log_message('debug', 'Added file to zip: ' . $zipEntryName);
          } else {
            log_message('error', 'File not found: ' . $filePath);
          }
        }
      }

      log_message('info', 'Added ' . $addedFiles . ' files to zip');

      $closeResult = $zip->close();
      log_message('info', 'Zip close result: ' . ($closeResult ? 'success' : 'failed'));

      if ($closeResult && file_exists($zipFilePath)) {
        log_message('info', 'Zip file created successfully, size: ' . filesize($zipFilePath) . ' bytes');

        // Download the zip file
        $response = $this->response->download($zipFilePath, null)->setFileName($zipFileName);

        // Clean up: delete the temporary zip file after download
        register_shutdown_function(function () use ($zipFilePath) {
          if (file_exists($zipFilePath)) {
            unlink($zipFilePath);
            log_message('info', 'Cleaned up temporary zip file');
          }
        });

        return $response;
      } else {
        log_message('error', 'Failed to create zip file or file does not exist after creation');
        return redirect()->back()->with('error', 'Gagal membuat file zip.');
      }
    } else {
      log_message('error', 'Failed to open zip file. ZipArchive error code: ' . $zipResult);
      return redirect()->back()->with('error', 'Gagal membuat file zip. Error code: ' . $zipResult);
    }
  }
}
