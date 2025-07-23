<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDesa extends Migration
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
      'kode_desa' => [
        'type' => 'VARCHAR',
        'constraint' => 20,
        'unique' => true,
      ],
      'luas' => [
        'type' => 'FLOAT',
        'null' => true,
      ],
      'kode_prov' => [
        'type' => 'VARCHAR',
        'constraint' => 10,
      ],
      'kode_kabupaten' => [
        'type' => 'VARCHAR',
        'constraint' => 10,
      ],
      'kode_kecamatan' => [
        'type' => 'VARCHAR',
        'constraint' => 10,
      ],
      'nama_provinsi' => [
        'type' => 'VARCHAR',
        'constraint' => 100,
      ],
      'nama_kabupaten' => [
        'type' => 'VARCHAR',
        'constraint' => 100,
      ],
      'nama_kecamatan' => [
        'type' => 'VARCHAR',
        'constraint' => 100,
      ],
      'nama_desa' => [
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
    $this->forge->createTable('desa');
  }

  public function down()
  {
    $this->forge->dropTable('desa');
  }
}
