<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class WalletController extends ResourceController
{
    protected $format = 'json';

    private function requireAuth()
    {
        $uid = session('user_id');
        if (! $uid) {
            return $this->failUnauthorized('Login required');
        }
        return $uid;
    }

    public function balance()
    {
        if (! $uid = $this->requireAuth()) return $uid;

        $wallet = model('WalletModel')->where('user_id', $uid)->first();

        return $this->respond(['balance' => (float) $wallet['balance']]);
    }

    public function deposit()
    {
        if (! $uid = $this->requireAuth()) {
            return $uid;
        }

        $amount = (float) ($this->request->getJSON(true)['amount'] ?? 0);
        if ($amount <= 0) {
            return $this->failValidationErrors('Amount must be > 0');
        }

        $db = db_connect();
        $db->transStart();

        // SELECT … FOR UPDATE para bloquear a carteira
        $wallet = $db->query(
            'SELECT * FROM wallets WHERE user_id = ? FOR UPDATE',
            [$uid]
        )->getRowArray();

        if (! $wallet) {
            $db->transComplete();
            return $this->failNotFound('Wallet not found');
        }

        // Atualiza saldo
        $newBalance = $wallet['balance'] + $amount;

        $db->table('wallets')
            ->where('id', $wallet['id'])
            ->update([
                'balance'    => $newBalance,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        model('TransactionModel')->insert([
            'wallet_id' => $wallet['id'],
            'type'      => 'deposit',
            'direction' => 'in',
            'amount'    => $amount,
        ]);

        $db->transComplete();

        return $this->respond(['balance' => $newBalance]);
    }


    public function transfer()
    {
        if (! $uid = $this->requireAuth()) return $uid;

        $data    = $this->request->getJSON(true);
        $amount  = (float) ($data['amount']   ?? 0);
        $toEmail =       ($data['toEmail']   ?? '');

        if ($amount <= 0) {
            return $this->failValidationErrors('Amount must be > 0');
        }
        if (! filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->failValidationErrors('Invalid e-mail');
        }

        $userTo = model('UserModel')->where('email', $toEmail)->first();
        if (! $userTo) {
            return $this->failNotFound('Recipient not found');
        }

        if ($uid === $userTo['id']) {
            return $this->failValidationErrors('Cannot transfer to the same account');
        }

        $db = db_connect();
        $db->transStart();

        $walletFrom = $db->query(
            'SELECT * FROM wallets WHERE user_id = ? FOR UPDATE',
            [$uid]
        )->getRowArray();

        $walletTo   = $db->query(
            'SELECT * FROM wallets WHERE user_id = ? FOR UPDATE',
            [$userTo['id']]
        )->getRowArray();
        

        if ($walletFrom['balance'] < $amount) {
            $db->transRollback();
            return $this->failResourceExists('Insufficient funds');
        }

        $db->table('wallets')->where('id', $walletFrom['id'])
            ->update([
                'balance' => $walletFrom['balance'] - $amount,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        $db->table('wallets')->where('id', $walletTo['id'])
            ->update([
                'balance' => $walletTo['balance'] + $amount,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        $db->table('transactions')->insert([
            'wallet_id' => $walletFrom['id'],
            'type'      => 'transfer',
            'direction' => 'out',
            'amount'    => $amount,
        ]);
        $outId = $db->insertID();

        $db->table('transactions')->insert([
            'wallet_id'      => $walletTo['id'],
            'type'           => 'transfer',
            'direction'      => 'in',
            'amount'         => $amount,
            'reversal_txn_id' => $outId,
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return $this->failServerError('Database error, rolled back');
        }

        return $this->respond(['message' => 'Transfer ok']);
    }
}
