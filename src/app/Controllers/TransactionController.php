<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class TransactionController extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';

    /**
     * Verifica autenticação via sessão e retorna o user_id ou resposta de falha.
     */
    private function requireAuth()
    {
        $uid = session('user_id');
        if (! $uid) {
            return $this->failUnauthorized('Login required');
        }
        return $uid;
    }

    /**
     * GET /api/transactions
     * Lista todas as transações do usuário autenticado.
     */
    public function index()
    {
        if (! $uid = $this->requireAuth()) {
            return $uid;
        }

        // Busca a carteira do usuário
        $wallet = model('WalletModel')->where('user_id', $uid)->first();
        if (! $wallet) {
            return $this->failNotFound('Wallet not found');
        }

        // Busca transações ordenadas por data
        $txns = model('TransactionModel')
            ->where('wallet_id', $wallet['id'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $this->respond($txns);
    }

    /**
     * POST /api/transactions/{id}/reverse
     * Reverte uma transação existente.
     */
    public function reverse($id = null)
    {
        if (! $uid = $this->requireAuth()) {
            return $uid;
        }

        if (! $id || ! is_numeric($id)) {
            return $this->failValidationErrors('Transaction ID invalid');
        }

        $txnModel = model('TransactionModel');
        $orig = $txnModel->find($id);
        if (! $orig) {
            return $this->failNotFound('Transaction not found');
        }

        // Apenas transações 'committed' podem ser revertidas
        if (isset($orig['status']) && $orig['status'] === 'reversed') {
            return $this->fail('Already reversed', 409);
        }

        // Verifica se pertence à carteira do usuário
        $wallet = model('WalletModel')->find($orig['wallet_id']);
        if (! $wallet || $wallet['user_id'] != $uid) {
            return $this->failForbidden('Not allowed');
        }

        $db = db_connect();
        $db->transStart();

        // Marca original como reversa
        $txnModel->update($id, ['status' => 'reversed']);

        // Insere operação de reversão
        $reverseData = [
            'wallet_id'        => $orig['wallet_id'],
            'type'             => 'reversal',
            'direction'        => $orig['direction'] ?? null,
            'amount'           => $orig['amount'],
            'status'           => 'committed',
            'reversal_txn_id'  => $id,
        ];
        $txnModel->insert($reverseData);

        // Ajusta saldo da carteira conforme tipo
        $delta = ($orig['direction'] === 'in' ? -1 : 1) * $orig['amount'];
        model('WalletModel')->update($wallet['id'], [
            'balance'    => $wallet['balance'] + $delta,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();
        if (! $db->transStatus()) {
            return $this->failServerError('Error reversing transaction');
        }

        return $this->respondCreated(['message' => 'Transaction reversed']);
    }
}
