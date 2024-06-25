<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = array(
            'name'=>'Admin',
            'username'=>'admin',
            'email'=>'admin@gmail.com',
            'password'=>password_hash('123456',PASSWORD_BCRYPT),
            'phone'=>'0768816767',
        );

        $this->db->table('users')->insert($data);
    }
}
