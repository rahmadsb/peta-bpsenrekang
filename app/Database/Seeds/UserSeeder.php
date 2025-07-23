<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
  public function run()
  {
    $users = [
      [
        'id' => Uuid::uuid4()->toString(),
        'username' => 'admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'ADMIN',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'username' => 'subject_matter',
        'password' => password_hash('subject123', PASSWORD_DEFAULT),
        'role' => 'SUBJECT_MATTER',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'username' => 'ipds',
        'password' => password_hash('ipds123', PASSWORD_DEFAULT),
        'role' => 'IPDS',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'id' => Uuid::uuid4()->toString(),
        'username' => 'guest',
        'password' => password_hash('guest123', PASSWORD_DEFAULT),
        'role' => 'GUEST',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
    ];

    $this->db->table('users')->insertBatch($users);
  }
}
