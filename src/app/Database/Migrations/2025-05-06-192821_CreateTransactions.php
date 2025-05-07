<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTransactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'wallet_id' => ['type' => 'INT', 'unsigned' => true],

            'type'      => ['type' => "ENUM('deposit','transfer','reversal')"],
            'direction' => ['type' => "ENUM('in','out')", 'null' => true],  // apenas transfer

            'amount'    => ['type' => 'DECIMAL', 'constraint' => '14,2'],

            'status' => [
                'type'    => "ENUM('committed','reversed')",
                'default' => 'committed',
            ],
            'reversal_txn_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('wallet_id');
        $this->forge->addKey('reversal_txn_id');
        $this->forge->addForeignKey('wallet_id',       'wallets',      'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('reversal_txn_id', 'transactions', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropForeignKey('transactions', 'transactions_reversal_txn_id_foreign');
        $this->forge->dropForeignKey('transactions', 'transactions_wallet_id_foreign');
        $this->forge->dropTable('transactions');
    }
}
