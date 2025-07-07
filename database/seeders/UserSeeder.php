<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add user
        $user = new User();
        $user->name = 'MATRIZA SISTEMAS E AUTOMACAO LTDA';
        $user->email = 'financeiro@matriza.com.br';
        $user->password = bcrypt('Aa123456');
        $user->save();
    }
}
