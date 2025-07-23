<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanDesa extends Migration
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
      'desa_uuid' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('kegiatan_desa');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan_desa');
  }
}
