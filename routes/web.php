<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\NotaController;

// Home → redireciona para turmas
Route::get('/', fn() => redirect()->route('turmas.index'));

// Turmas
Route::resource('turmas', TurmaController::class);
Route::patch('turmas/{turma}/fechar', [TurmaController::class, 'fechar'])->name('turmas.fechar');
Route::patch('turmas/{turma}/reabrir', [TurmaController::class, 'reabrir'])->name('turmas.reabrir');

// Alunos
Route::resource('turmas.alunos', AlunoController::class)->shallow();

// Notas
Route::get('alunos/{aluno}/notas',            [NotaController::class, 'index'])->name('notas.index');
Route::get('alunos/{aluno}/notas/create',     [NotaController::class, 'create'])->name('notas.create');
Route::post('alunos/{aluno}/notas',           [NotaController::class, 'store'])->name('notas.store');
Route::get('alunos/{aluno}/notas/edit',       [NotaController::class, 'edit'])->name('notas.edit');
Route::put('alunos/{aluno}/notas',            [NotaController::class, 'update'])->name('notas.update');
Route::get('alunos/{aluno}/notas/resultado',  [NotaController::class, 'resultado'])->name('notas.resultado');

// Recuperação
Route::get('alunos/{aluno}/recuperacao',      [NotaController::class, 'recuperacaoForm'])->name('notas.recuperacao.form');
Route::post('alunos/{aluno}/recuperacao',     [NotaController::class, 'recuperacaoStore'])->name('notas.recuperacao.store');
