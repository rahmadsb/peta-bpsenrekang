<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanSls extends Migration
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
      'id_sls' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('id_kegiatan', 'kegiatan', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_sls', 'sls', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('kegiatan_sls');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan_sls');
  }
}
