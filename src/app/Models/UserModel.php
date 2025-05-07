<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'created_at', 'updated_at'];
    protected $returnType = 'array';

    protected $useTimestamps = true; // preenche created_at / updated_at
}
