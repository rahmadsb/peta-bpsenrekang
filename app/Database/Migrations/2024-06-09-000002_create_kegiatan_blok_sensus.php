<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanBlokSensus extends Migration
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
      'id_blok_sensus' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('id_kegiatan', 'kegiatan', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_blok_sensus', 'blok_sensus', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('kegiatan_blok_sensus');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan_blok_sensus');
  }
}
