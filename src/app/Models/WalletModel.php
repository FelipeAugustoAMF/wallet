<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table      = 'wallets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'balance', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false; // nós mesmos gerenciaremos updated_at
}
