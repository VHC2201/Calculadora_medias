<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Nota;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    // Exibe notas e resultado do aluno
    public function index(Aluno $aluno)
    {
        $nota = $aluno->nota;
        return view('notas.index', compact('aluno', 'nota'));
    }

    // Formulário para lançar notas
    public function create(Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada. Não é possível lançar notas.');
        }

        if ($aluno->nota) {
            return redirect()->route('notas.edit', $aluno)
                ->with('info', 'Este aluno já possui notas lançadas. Use a edição.');
        }

        return view('notas.create', compact('aluno'));
    }

    // Salva notas pela primeira vez
    public function store(Request $request, Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada. Não é possível lançar notas.');
        }

        $validated = $request->validate([
            'bimestre1' => 'required|numeric|min:0|max:10',
            'bimestre2' => 'required|numeric|min:0|max:10',
            'bimestre3' => 'required|numeric|min:0|max:10',
            'bimestre4' => 'required|numeric|min:0|max:10',
        ]);

        $media    = Nota::calcularMedia(...array_values($validated));
        $conceito = Nota::calcularConceito($media);

        $aluno->nota()->create(array_merge($validated, [
            'media'    => $media,
            'conceito' => $conceito,
        ]));

        return redirect()->route('notas.resultado', $aluno);
    }

    // Formulário para editar notas
    public function edit(Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada. Não é possível editar notas.');
        }

        $nota = $aluno->nota;

        if (!$nota) {
            return redirect()->route('notas.create', $aluno);
        }

        return view('notas.edit', compact('aluno', 'nota'));
    }

    // Atualiza notas
    public function update(Request $request, Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada. Não é possível editar notas.');
        }

        $validated = $request->validate([
            'bimestre1' => 'required|numeric|min:0|max:10',
            'bimestre2' => 'required|numeric|min:0|max:10',
            'bimestre3' => 'required|numeric|min:0|max:10',
            'bimestre4' => 'required|numeric|min:0|max:10',
        ]);

        $media    = Nota::calcularMedia(...array_values($validated));
        $conceito = Nota::calcularConceito($media);

        // Se o conceito mudou de C para outro, limpa a recuperação
        $limpaCuperacao = ($conceito !== 'C');

        $aluno->nota()->updateOrCreate(
            ['aluno_id' => $aluno->id],
            array_merge($validated, [
                'media'                => $media,
                'conceito'             => $conceito,
                'nota_recuperacao'     => $limpaCuperacao ? null : $aluno->nota?->nota_recuperacao,
                'aprovado_recuperacao' => $limpaCuperacao ? null : $aluno->nota?->aprovado_recuperacao,
            ])
        );

        return redirect()->route('notas.resultado', $aluno)
            ->with('success', 'Notas atualizadas com sucesso!');
    }

    // Tela de resultado
    public function resultado(Aluno $aluno)
    {
        $nota = $aluno->nota;

        if (!$nota) {
            return redirect()->route('notas.create', $aluno);
        }

        $situacao = Nota::calcularSituacao($nota->conceito);

        return view('notas.resultado', compact('aluno', 'nota', 'situacao'));
    }

    // Formulário de recuperação
    public function recuperacaoForm(Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada. Não é possível lançar recuperação.');
        }

        $nota = $aluno->nota;

        if (!$nota || $nota->conceito !== 'C') {
            return redirect()->route('notas.resultado', $aluno)
                ->with('error', 'Este aluno não está em recuperação.');
        }

        return view('notas.recuperacao', compact('aluno', 'nota'));
    }

    // Salva nota de recuperação
    public function recuperacaoStore(Request $request, Aluno $aluno)
    {
        if ($aluno->turma->isFechada()) {
            return redirect()->route('turmas.show', $aluno->turma)
                ->with('error', 'Esta turma está fechada. Não é possível lançar recuperação.');
        }

        $request->validate([
            'nota_recuperacao' => 'required|numeric|min:0|max:10',
        ]);

        $nota             = $aluno->nota;
        $notaRec          = (float) $request->nota_recuperacao;
        $aprovado         = Nota::calcularRecuperacao($nota->media, $notaRec);

        $nota->update([
            'nota_recuperacao'     => $notaRec,
            'aprovado_recuperacao' => $aprovado,
        ]);

        return redirect()->route('notas.resultado', $aluno)
            ->with('success', 'Recuperação registrada com sucesso!');
    }
}
