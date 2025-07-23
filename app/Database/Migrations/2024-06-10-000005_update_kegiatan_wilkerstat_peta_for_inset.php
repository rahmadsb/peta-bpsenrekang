<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateKegiatanWilkerstatPetaForInset extends Migration
{
  public function up()
  {
    // Hapus data inset lama
    if ($this->db->fieldExists('is_inset', 'kegiatan_wilkerstat_peta')) {
      $this->db->table('kegiatan_wilkerstat_peta')->where('is_inset', 1)->delete();
      $this->forge->dropColumn('kegiatan_wilkerstat_peta', 'is_inset');
    }
    // Tambah kolom parent_peta_id jika belum ada
    if (!$this->db->fieldExists('parent_peta_id', 'kegiatan_wilkerstat_peta')) {
      $this->forge->addColumn('kegiatan_wilkerstat_peta', [
        'parent_peta_id' => [
          'type' => 'INT',
          'constraint' => 11,
          'unsigned' => true,
          'null' => true,
        ]
      ]);
    }
  }

  public function down()
  {
    if ($this->db->fieldExists('parent_peta_id', 'kegiatan_wilkerstat_peta')) {
      $this->forge->dropColumn('kegiatan_wilkerstat_peta', 'parent_peta_id');
    }
    if (!$this->db->fieldExists('is_inset', 'kegiatan_wilkerstat_peta')) {
      $this->forge->addColumn('kegiatan_wilkerstat_peta', [
        'is_inset' => [
          'type' => 'BOOLEAN',
          'default' => false,
        ]
      ]);
    }
  }
}
