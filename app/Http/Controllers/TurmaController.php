<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function index()
    {
        $turmas = Turma::withCount('alunos')->orderByDesc('created_at')->get();
        return view('turmas.index', compact('turmas'));
    }

    public function create()
    {
        return view('turmas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'    => 'required|string|max:100',
            'ano'     => 'nullable|string|max:10',
            'periodo' => 'nullable|string|max:20',
        ]);

        Turma::create($validated);

        return redirect()->route('turmas.index')
            ->with('success', 'Turma cadastrada com sucesso!');
    }

    public function show(Turma $turma)
    {
        $alunos = $turma->alunos()->with('nota')->orderBy('nome')->get();
        return view('turmas.show', compact('turma', 'alunos'));
    }

    public function edit(Turma $turma)
    {
        if ($turma->isFechada()) {
            return redirect()->route('turmas.show', $turma)
                ->with('error', 'Esta turma está fechada e não pode ser editada.');
        }
        return view('turmas.edit', compact('turma'));
    }

    public function update(Request $request, Turma $turma)
    {
        if ($turma->isFechada()) {
            return redirect()->route('turmas.show', $turma)
                ->with('error', 'Esta turma está fechada e não pode ser editada.');
        }

        $validated = $request->validate([
            'nome'    => 'required|string|max:100',
            'ano'     => 'nullable|string|max:10',
            'periodo' => 'nullable|string|max:20',
        ]);

        $turma->update($validated);

        return redirect()->route('turmas.show', $turma)
            ->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();
        return redirect()->route('turmas.index')
            ->with('success', 'Turma removida com sucesso!');
    }

    public function fechar(Turma $turma)
    {
        $turma->update(['fechada' => true]);
        return redirect()->route('turmas.show', $turma)
            ->with('success', 'Turma fechada! Não será mais possível realizar alterações.');
    }

    public function reabrir(Turma $turma)
    {
        $turma->update(['fechada' => false]);
        return redirect()->route('turmas.show', $turma)
            ->with('success', 'Turma reaberta com sucesso!');
    }
}
