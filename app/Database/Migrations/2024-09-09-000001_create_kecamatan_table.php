<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKecamatanTable extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id' => [
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => true,
        'auto_increment' => true,
      ],
      'kode_prov' => [
        'type'       => 'VARCHAR',
        'constraint' => '2',
      ],
      'nama_provinsi' => [
        'type'       => 'VARCHAR',
        'constraint' => '255',
      ],
      'kode_kabupaten' => [
        'type'       => 'VARCHAR',
        'constraint' => '4',
      ],
      'nama_kabupaten' => [
        'type'       => 'VARCHAR',
        'constraint' => '255',
      ],
      'kode_kecamatan' => [
        'type'       => 'VARCHAR',
        'constraint' => '6',
      ],
      'nama_kecamatan' => [
        'type'       => 'VARCHAR',
        'constraint' => '255',
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
    $this->forge->createTable('kecamatan');
  }

  public function down()
  {
    $this->forge->dropTable('kecamatan');
  }
}
