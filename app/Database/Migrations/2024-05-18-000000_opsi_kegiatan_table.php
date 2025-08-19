<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OpsiKegiatan extends Migration
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
    $this->forge->addKey('id', true);
    $this->forge->createTable('opsi_kegiatan');
  }

  public function down()
  {
    $this->forge->dropTable('opsi_kegiatan');
  }
}
