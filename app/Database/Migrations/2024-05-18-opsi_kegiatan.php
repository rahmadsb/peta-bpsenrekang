<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OpsiKegiatan extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'uuid' => [
        'type' => 'CHAR',
        'constraint' => 36,
        'null' => false,
        'unique' => true,
      ],
      'kode_kegiatan' => [
        'type' => 'VARCHAR',
        'constraint' => 50,
      ],
      'nama_kegiatan' => [
        'type' => 'VARCHAR',
        'constraint' => 100,
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
    $this->forge->addKey('uuid', true);
    $this->forge->createTable('kegiatan_option');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan_option');
  }
}
