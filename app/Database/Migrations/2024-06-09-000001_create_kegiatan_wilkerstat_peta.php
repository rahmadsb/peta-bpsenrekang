<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanWilkerstatPeta extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id' => [
        'type' => 'CHAR',
        'constraint' => 36,
        'null' => false,
        'unique' => true,
      ],
      'id_kegiatan' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
      'wilkerstat_type' => [
        'type' => 'ENUM',
        'constraint' => ['blok_sensus', 'sls', 'desa'],
      ],
      'id_wilkerstat' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
      'jenis_peta' => [
        'type' => 'ENUM',
        'constraint' => ['dengan_titik', 'tanpa_titik'],
      ],
      'id_parent_peta' => [
        'type' => 'CHAR',
        'constraint' => 36,
        'null' => true,
      ],
      'file_path' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
      ],
      'nama_file' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
      ],
      'uploaded_at' => [
        'type' => 'DATETIME',
        'null' => true,
      ],
      'uploader' => [
        'type' => 'VARCHAR',
        'constraint' => 50,
        'null' => true,
      ],
      'created_at' => [
        'type' => 'DATETIME',
        'null' => true,
      ],
      'updated_at' => [
        'type' => 'DATETIME',
        'null' => true,
      ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('id_kegiatan', 'kegiatan', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_parent_peta', 'kegiatan_wilkerstat_peta', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('kegiatan_wilkerstat_peta');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan_wilkerstat_peta');
  }
}
