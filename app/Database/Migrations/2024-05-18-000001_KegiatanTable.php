<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kegiatan extends Migration
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
      'kode_kegiatan_option' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
      'id_user' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
      'tahun' => [
        'type' => 'INT',
        'constraint' => 4,
      ],
      'bulan' => [
        'type' => 'VARCHAR',
        'constraint' => 20,
      ],
      'tanggal_batas_cetak' => [
        'type' => 'DATE',
        'null' => true,
      ],
      'status' => [
        'type' => 'VARCHAR',
        'constraint' => 50,
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
    $this->forge->createTable('kegiatan');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan');
  }
}
