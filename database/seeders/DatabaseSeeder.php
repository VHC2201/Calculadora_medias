<?php

namespace Database\Seeders;

use App\Models\Turma;
use App\Models\Aluno;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Turma de exemplo
        $turma = Turma::create([
            'nome'    => '3º Ano A',
            'ano'     => '2025',
            'periodo' => 'Manhã',
        ]);

        $alunos = [
            ['nome' => 'Ana Paula Silva',    'matricula' => '2025001'],
            ['nome' => 'Bruno Costa Santos', 'matricula' => '2025002'],
            ['nome' => 'Carla Mendes',       'matricula' => '2025003'],
        ];

        foreach ($alunos as $a) {
            $turma->alunos()->create($a);
        }
    }
}
