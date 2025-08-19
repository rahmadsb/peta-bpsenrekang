<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kegiatan extends Migration
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
      'id_opsi_kegiatan' => [
        'type' => 'CHAR',
        'constraint' => 36,
      ],
      'kode_opsi_kegiatan' => [
        'type' => 'VARCHAR',
        'constraint' => 50,
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
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('id_opsi_kegiatan', 'opsi_kegiatan', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('kegiatan');
  }

  public function down()
  {
    $this->forge->dropTable('kegiatan');
  }
}
