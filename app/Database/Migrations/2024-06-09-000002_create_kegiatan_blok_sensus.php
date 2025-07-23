<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanBlokSensus extends Migration
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
      'blok_sensus_uuid' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('kegiatan_blok_sensus');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan_blok_sensus');
  }
}
