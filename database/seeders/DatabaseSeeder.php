<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->createUsers();
    }

    private function createUsers()
    {
        User::create([
            'name' => 'João Gerente',
            'email' => 'joao@email.com',
            'password' => Hash::make('20212021'),
            'type' => User::TYPE_GERENTE,
        ]);

        User::create([
            'name' => 'Chico Técnico',
            'email' => 'chico@email.com',
            'password' => Hash::make('20212021'),
            'type' => User::TYPE_TECNICO_MEDIO,
        ]);

        User::create([
            'name' => 'Geraldo',
            'email' => 'geraldo@email.com',
            'password' => Hash::make('20212021'),
            'type' => User::TYPE_TECNICO_SUPERIOR,
        ]);


    }
}
