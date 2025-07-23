<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
  protected $table = 'users';
  protected $primaryKey = 'id';
  protected $useAutoIncrement = false;
  protected $allowedFields = ['id', 'username', 'password', 'role', 'created_at', 'updated_at'];
  protected $returnType = 'array';

  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';
  protected $deletedField = 'deleted_at';

  protected $validationRules = [];
  protected $validationMessages = [];
}
