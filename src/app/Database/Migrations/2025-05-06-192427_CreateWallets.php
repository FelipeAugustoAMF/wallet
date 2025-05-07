<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWallets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'balance'    => ['type' => 'DECIMAL', 'constraint' => '14,2', 'default' => '0.00'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('wallets');
    }

    public function down()
    {
        // Precisa soltar FK antes de dropar a tabela
        $this->forge->dropForeignKey('wallets', 'wallets_user_id_foreign');
        $this->forge->dropTable('wallets');
    }
}
