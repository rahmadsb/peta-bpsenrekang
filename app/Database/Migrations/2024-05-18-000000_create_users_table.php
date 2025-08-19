<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id' => [
        'type'       => 'CHAR',
        'constraint' => 36,
        'null'       => false,
        'unique'     => true,
      ],
      'username' => [
        'type'       => 'VARCHAR',
        'constraint' => '50',
        'unique'     => true,
      ],
      'password' => [
        'type'       => 'VARCHAR',
        'constraint' => '255',
      ],
      'role' => [
        'type'       => 'VARCHAR',
        'constraint' => '20',
        'default'    => 'user',
      ],
      'created_at' => [
        'type'    => 'DATETIME',
        'null'    => true,
      ],
      'updated_at' => [
        'type'    => 'DATETIME',
        'null'    => true,
      ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('users');
  }

  public function down()
  {
    $this->forge->dropTable('users');
  }
}
