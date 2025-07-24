<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanWilkerstatPeta extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id' => [
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => true,
        'auto_increment' => true,
      ],
      'kegiatan_uuid' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
      'wilkerstat_type' => [
        'type' => 'ENUM',
        'constraint' => ['blok_sensus', 'sls', 'desa'],
      ],
      'wilkerstat_uuid' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
      'jenis_peta' => [
        'type' => 'ENUM',
        'constraint' => ['dengan_titik', 'tanpa_titik'],
      ],
      'parent_peta_id' => [
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => true,
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
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('kegiatan_wilkerstat_peta');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan_wilkerstat_peta');
  }
}
