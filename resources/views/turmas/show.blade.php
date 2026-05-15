@extends('layouts.app')
@section('title', $turma->nome)

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('turmas.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-800 mb-0">
                {{ $turma->nome }}
                @if($turma->isFechada())
                    <span class="badge bg-danger ms-2" style="font-size:.65rem;vertical-align:middle">FECHADA</span>
                @endif
            </h2>
            <small class="text-muted">
                @if($turma->ano) <i class="bi bi-calendar3 me-1"></i>{{ $turma->ano }} @endif
                @if($turma->periodo) <i class="bi bi-clock me-1 ms-2"></i>{{ $turma->periodo }} @endif
            </small>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        @if($turma->isFechada())
            {{-- Banner + botão reabrir --}}
            <span class="turma-fechada-banner d-flex align-items-center gap-2">
                <i class="bi bi-lock-fill"></i> Turma Fechada
            </span>
            <form action="{{ route('turmas.reabrir', $turma) }}" method="POST"
                  onsubmit="return confirm('Reabrir esta turma?')">
                @csrf @method('PATCH')
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-unlock me-1"></i>Reabrir
                </button>
            </form>
        @else
            <a href="{{ route('turmas.alunos.create', $turma) }}" class="btn btn-success">
                <i class="bi bi-person-plus-fill me-1"></i>Adicionar Aluno
            </a>
            <a href="{{ route('turmas.edit', $turma) }}" class="btn btn-outline-warning">
                <i class="bi bi-pencil me-1"></i>Editar
            </a>
            <form action="{{ route('turmas.fechar', $turma) }}" method="POST"
                  onsubmit="return confirm('Fechar esta turma? Isso impedirá qualquer alteração futura.')">
                @csrf @method('PATCH')
                <button class="btn btn-danger">
                    <i class="bi bi-lock-fill me-1"></i>Fechar Turma
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Alerta turma fechada --}}
@if($turma->isFechada())
<div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-lock-fill fs-5"></i>
    <div><strong>Turma fechada.</strong> Nenhuma alteração pode ser realizada. Para editar, clique em "Reabrir".</div>
</div>
@endif

{{-- Stats --}}
@php
    $total       = $alunos->count();
    $comNotas    = $alunos->filter(fn($a) => $a->nota)->count();

    // Aprovados = conceito A ou B  +  conceito C com aprovado_recuperacao = true
    $aprovados   = $alunos->filter(fn($a) =>
        $a->nota && (
            in_array($a->nota->conceito, ['A','B']) ||
            ($a->nota->conceito === 'C' && $a->nota->aprovado_recuperacao === true)
        )
    )->count();

    // Em recuperação = conceito C que ainda NÃO fez a prova de recuperação
    $recuperacao = $alunos->filter(fn($a) =>
        $a->nota && $a->nota->conceito === 'C' && $a->nota->nota_recuperacao === null
    )->count();

    // Reprovados = conceito D  +  conceito C com aprovado_recuperacao = false
    $reprovados  = $alunos->filter(fn($a) =>
        $a->nota && (
            $a->nota->conceito === 'D' ||
            ($a->nota->conceito === 'C' && $a->nota->aprovado_recuperacao === false)
        )
    )->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#4361ee,#3a0ca3)">
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total de Alunos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#2dc653,#1a8a35)">
            <div class="stat-number">{{ $aprovados }}</div>
            <div class="stat-label">Aprovados</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f4a261,#e76f51)">
            <div class="stat-number">{{ $recuperacao }}</div>
            <div class="stat-label">Recuperação</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#e63946,#c1121f)">
            <div class="stat-number">{{ $reprovados }}</div>
            <div class="stat-label">Reprovados</div>
        </div>
    </div>
</div>

{{-- Lista de alunos --}}
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-700 text-dark"><i class="bi bi-people me-2 text-primary"></i>Alunos da Turma</span>
        <span class="badge bg-primary rounded-pill">{{ $total }}</span>
    </div>

    @if($alunos->isEmpty())
    <div class="card-body text-center py-5">
        <i class="bi bi-person-x display-3 text-muted"></i>
        <h5 class="mt-3 text-muted">Nenhum aluno cadastrado</h5>
        @if(!$turma->isFechada())
        <a href="{{ route('turmas.alunos.create', $turma) }}" class="btn btn-success mt-2">
            <i class="bi bi-person-plus me-1"></i>Cadastrar primeiro aluno
        </a>
        @endif
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Aluno</th>
                    <th>Matrícula</th>
                    <th class="text-center">1º Bim</th>
                    <th class="text-center">2º Bim</th>
                    <th class="text-center">3º Bim</th>
                    <th class="text-center">4º Bim</th>
                    <th class="text-center">Média</th>
                    <th class="text-center">Conceito</th>
                    <th class="text-center">Situação</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alunos as $aluno)
                @php $nota = $aluno->nota; @endphp
                <tr>
                    <td class="fw-700">
                        <i class="bi bi-person-circle me-2 text-primary"></i>{{ $aluno->nome }}
                    </td>
                    <td class="text-muted small">{{ $aluno->matricula ?: '—' }}</td>

                    @if($nota)
                        <td class="text-center">{{ number_format($nota->bimestre1, 1) }}</td>
                        <td class="text-center">{{ number_format($nota->bimestre2, 1) }}</td>
                        <td class="text-center">{{ number_format($nota->bimestre3, 1) }}</td>
                        <td class="text-center">{{ number_format($nota->bimestre4, 1) }}</td>
                        <td class="text-center fw-700 fs-5">{{ number_format($nota->media, 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $nota->conceitoBadgeClass }} rounded-pill px-3 py-2 fw-700">
                                {{ $nota->conceito }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($nota->conceito === 'C')
                                @if($nota->recuperacaoRealizada())
                                    <span class="badge {{ $nota->aprovado_recuperacao ? 'bg-success' : 'bg-danger' }}">
                                        {{ $nota->aprovado_recuperacao ? 'Aprov. Rec.' : 'Reprovado' }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">Aguard. Rec.</span>
                                @endif
                            @elseif(in_array($nota->conceito, ['A','B']))
                                <span class="badge bg-success">Aprovado</span>
                            @else
                                <span class="badge bg-danger">Reprovado</span>
                            @endif
                        </td>
                    @else
                        <td colspan="6" class="text-center text-muted fst-italic small">Notas não lançadas</td>
                        <td class="text-center"><span class="badge bg-secondary">Pendente</span></td>
                    @endif

                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            @if(!$nota && !$turma->isFechada())
                                <a href="{{ route('notas.create', $aluno) }}" class="btn btn-success btn-sm" title="Lançar notas">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                            @endif
                            @if($nota)
                                <a href="{{ route('notas.resultado', $aluno) }}" class="btn btn-primary btn-sm" title="Ver resultado">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(!$turma->isFechada())
                                <a href="{{ route('notas.edit', $aluno) }}" class="btn btn-outline-warning btn-sm" title="Editar notas">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($nota->conceito === 'C' && !$nota->recuperacaoRealizada())
                                <a href="{{ route('notas.recuperacao.form', $aluno) }}" class="btn btn-warning btn-sm" title="Lançar recuperação">
                                    <i class="bi bi-arrow-repeat"></i>
                                </a>
                                @endif
                                @endif
                            @endif
                            @if(!$turma->isFechada())
                            <a href="{{ route('alunos.edit', $aluno) }}" class="btn btn-outline-secondary btn-sm" title="Editar aluno">
                                <i class="bi bi-person-gear"></i>
                            </a>
                            <form action="{{ route('alunos.destroy', $aluno) }}" method="POST"
                                  onsubmit="return confirm('Remover aluno {{ $aluno->nome }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
