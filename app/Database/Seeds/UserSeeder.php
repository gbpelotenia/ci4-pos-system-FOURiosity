<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $exists = $this->db->table('users')->where('username', 'admin')->get()->getRowArray();

        if ($exists) {
            return;
        }

        $this->db->table('users')->insert([
            'username' => 'admin',
            'full_name' => 'System Administrator',
            'password_hash' => $password,
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
