<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'wallet_id',
        'type',
        'status',
        'direction',
        'amount',
        'reversal_txn_id',
        'created_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
