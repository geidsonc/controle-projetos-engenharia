<?php

namespace Database\Seeders;

use App\Models\Project;
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
        $this->createProjects();
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

    private function createProjects()
    {
        $project1 = Project::create([
            'name' => 'Construção de reservatórios de água',
            'process_number' => '200',
            'agreement_number' => '2021-A200-01',
            'action' => 'SAA',
            'start_date' => '2021-01-10',
            'end_date' => '2021-06-30',
            'city' => 'Juazeiro - Salitre',
            'resume' => '',
        ]);

        $project1->projectStatus()->create([
            'user_id' => 2,
            'technical_opinion' => 'Projeto na fase de construção dos reservatórios',
            'status' => '3',
        ]);

        $project1->users()->sync([2]);

        $project2 = Project::create([
            'name' => 'Construção de sistema de tratamento de esgoto na comunidade rural de Porção',
            'process_number' => '201',
            'agreement_number' => '2021-B201-02',
            'action' => 'SES',
            'start_date' => '2021-03-20',
            'end_date' => '2021-12-30',
            'city' => 'Juazeiro - Porção',
            'resume' => 'A implantação de redes coletoras de esgoto em áreas rurais é extremamente onerosa, visto que as moradias se encontram distantes entre si. Além disso, a topografia do terreno nem sempre é favorável. A maioria dos sistemas é constituída por fossa séptica e sumidouro. É necessário aprimorar estes sistemas e criar novas tecnologias de sistemas descentralizados de tratamento de esgoto, que atenda os padrões da legislação vigente'
        ]);

        $project2->projectStatus()->create([
            'user_id' => 3,
            'technical_opinion' => 'Aguardando envio de documentação',
            'status' => '1',
        ]);

        $project2->users()->sync([2,3]);
    }

}
