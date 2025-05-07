<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DevSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->insert([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $userId = $this->db->insertID();

        $this->db->table('wallets')->insert([
            'user_id'    => $userId,
            'balance'    => 100.00,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
