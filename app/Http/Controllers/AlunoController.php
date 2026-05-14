<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function create(Turma $turma)
    {
        if ($turma->isFechada()) {
            return redirect()->route('turmas.show', $turma)
                ->with('error', 'Esta turma está fechada. Não é possível adicionar alunos.');
        }
        return view('alunos.create', compact('turma'));
    }

    public function store(Request $request, Turma $turma)
    {
        if ($turma->isFechada()) {
            return redirect()->route('turmas.show', $turma)
                ->with('error', 'Esta turma está fechada. Não é possível adicionar alunos.');
        }

        $validated = $request->validate([
            'nome'      => 'required|string|max:150',
            'matricula' => 'nullable|string|max:30',
        ]);

        $turma->alunos()->create($validated);

        return redirect()->route('turmas.show', $turma)
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function edit(Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada e não pode ser editada.');
        }
        return view('alunos.edit', compact('aluno'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada e não pode ser editada.');
        }

        $validated = $request->validate([
            'nome'      => 'required|string|max:150',
            'matricula' => 'nullable|string|max:30',
        ]);

        $aluno->update($validated);

        return redirect()->route('turmas.show', $aluno->turma)
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada e não pode ser editada.');
        }

        $turma = $aluno->turma;
        $aluno->delete();

        return redirect()->route('turmas.show', $turma)
            ->with('success', 'Aluno removido com sucesso!');
    }
}
