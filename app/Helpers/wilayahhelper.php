<?php

if (!function_exists('get_wilayah_data')) {
  /**
   * Mendapatkan data wilayah dari file Excel
   * 
   * @param string $file Nama file Excel (kode_bs.xlsx atau kode_sls.xlsx)
   * @return array Data wilayah
   */
  function get_wilayah_data($file = 'kode_bs.xlsx')
  {
    try {
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      $spreadsheet = $reader->load(ROOTPATH . $file);
      $worksheet = $spreadsheet->getActiveSheet();
      $data = $worksheet->toArray();

      // Asumsikan baris pertama adalah header
      $header = array_shift($data);

      $result = [
        'provinsi' => [],
        'kabupaten' => [],
        'kecamatan' => [],
        'desa' => [],
        'sls' => [],
        'bs' => []
      ];

      foreach ($data as $row) {
        // Mapping kolom berdasarkan header Excel
        // Asumsikan struktur kolom: [kode_prov, nama_prov, kode_kab, nama_kab, kode_kec, nama_kec, kode_desa, nama_desa, kode_sls, nama_sls, kode_bs, nama_bs]
        $kode_prov = isset($row[0]) ? trim($row[0]) : '';
        $nama_prov = isset($row[1]) ? trim($row[1]) : '';
        $kode_kab = isset($row[2]) ? trim($row[2]) : '';
        $nama_kab = isset($row[3]) ? trim($row[3]) : '';
        $kode_kec = isset($row[4]) ? trim($row[4]) : '';
        $nama_kec = isset($row[5]) ? trim($row[5]) : '';
        $kode_desa = isset($row[6]) ? trim($row[6]) : '';
        $nama_desa = isset($row[7]) ? trim($row[7]) : '';
        $kode_sls = isset($row[8]) ? trim($row[8]) : '';
        $nama_sls = isset($row[9]) ? trim($row[9]) : '';
        $kode_bs = isset($row[10]) ? trim($row[10]) : '';
        $nama_bs = isset($row[11]) ? trim($row[11]) : '';

        // Hanya tambahkan jika kode tidak kosong dan belum ada di hasil
        if (!empty($kode_prov) && !isset($result['provinsi'][$kode_prov])) {
          $result['provinsi'][$kode_prov] = $nama_prov;
        }

        if (!empty($kode_kab) && !isset($result['kabupaten'][$kode_prov][$kode_kab])) {
          $result['kabupaten'][$kode_prov][$kode_kab] = $nama_kab;
        }

        if (!empty($kode_kec) && !isset($result['kecamatan'][$kode_prov][$kode_kab][$kode_kec])) {
          $result['kecamatan'][$kode_prov][$kode_kab][$kode_kec] = $nama_kec;
        }

        if (!empty($kode_desa) && !isset($result['desa'][$kode_prov][$kode_kab][$kode_kec][$kode_desa])) {
          $result['desa'][$kode_prov][$kode_kab][$kode_kec][$kode_desa] = $nama_desa;
        }

        if (!empty($kode_sls) && !isset($result['sls'][$kode_desa][$kode_sls])) {
          $result['sls'][$kode_desa][$kode_sls] = $nama_sls;
        }

        if (!empty($kode_bs) && !isset($result['bs'][$kode_sls][$kode_bs])) {
          $result['bs'][$kode_sls][$kode_bs] = $nama_bs;
        }
      }

      return $result;
    } catch (\Exception $e) {
      // Fallback jika file tidak bisa dibaca
      log_message('error', 'Error reading Excel file: ' . $e->getMessage());

      // Return data dummy
      return [
        'provinsi' => ['73' => 'SULAWESI SELATAN'],
        'kabupaten' => ['73' => ['7318' => 'KABUPATEN ENREKANG']],
        'kecamatan' => ['73' => ['7318' => ['731804' => 'MAIWA']]],
        'desa' => ['73' => ['7318' => ['731804' => ['7318042001' => 'BANGKALA']]]],
        'sls' => ['7318042001' => ['001' => 'SLS 001']],
        'bs' => ['001' => ['00101' => 'BS 00101']]
      ];
    }
  }
}

if (!function_exists('get_provinsi')) {
  /**
   * Mendapatkan daftar provinsi
   * 
   * @return array Daftar provinsi [kode => nama]
   */
  function get_provinsi()
  {
    $data = get_wilayah_data();
    return $data['provinsi'];
  }
}

if (!function_exists('get_kabupaten')) {
  /**
   * Mendapatkan daftar kabupaten berdasarkan kode provinsi
   * 
   * @param string $kode_prov Kode provinsi
   * @return array Daftar kabupaten [kode => nama]
   */
  function get_kabupaten($kode_prov)
  {
    $data = get_wilayah_data();
    return isset($data['kabupaten'][$kode_prov]) ? $data['kabupaten'][$kode_prov] : [];
  }
}

if (!function_exists('get_kecamatan')) {
  /**
   * Mendapatkan daftar kecamatan berdasarkan kode provinsi dan kabupaten
   * 
   * @param string $kode_prov Kode provinsi
   * @param string $kode_kab Kode kabupaten
   * @return array Daftar kecamatan [kode => nama]
   */
  function get_kecamatan($kode_prov, $kode_kab)
  {
    $data = get_wilayah_data();
    return isset($data['kecamatan'][$kode_prov][$kode_kab]) ? $data['kecamatan'][$kode_prov][$kode_kab] : [];
  }
}

if (!function_exists('get_desa')) {
  /**
   * Mendapatkan daftar desa berdasarkan kode provinsi, kabupaten, dan kecamatan
   * 
   * @param string $kode_prov Kode provinsi
   * @param string $kode_kab Kode kabupaten
   * @param string $kode_kec Kode kecamatan
   * @return array Daftar desa [kode => nama]
   */
  function get_desa($kode_prov, $kode_kab, $kode_kec)
  {
    $data = get_wilayah_data();
    return isset($data['desa'][$kode_prov][$kode_kab][$kode_kec]) ? $data['desa'][$kode_prov][$kode_kab][$kode_kec] : [];
  }
}

if (!function_exists('get_sls')) {
  /**
   * Mendapatkan daftar SLS berdasarkan kode desa
   * 
   * @param string $kode_desa Kode desa
   * @return array Daftar SLS [kode => nama]
   */
  function get_sls($kode_desa)
  {
    $data = get_wilayah_data();
    return isset($data['sls'][$kode_desa]) ? $data['sls'][$kode_desa] : [];
  }
}

if (!function_exists('get_bs')) {
  /**
   * Mendapatkan daftar Blok Sensus berdasarkan kode SLS
   * 
   * @param string $kode_sls Kode SLS
   * @return array Daftar BS [kode => nama]
   */
  function get_bs($kode_sls)
  {
    $data = get_wilayah_data();
    return isset($data['bs'][$kode_sls]) ? $data['bs'][$kode_sls] : [];
  }
}
