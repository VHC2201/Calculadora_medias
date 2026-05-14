@extends('layouts.app')
@section('title', 'Resultado — ' . $aluno->nome)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Cabeçalho --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('turmas.show', $aluno->turma) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Resultado do Aluno</h2>
                <small class="text-muted">
                    <i class="bi bi-people me-1"></i>{{ $aluno->turma->nome }}
                    @if($aluno->turma->ano) · {{ $aluno->turma->ano }} @endif
                </small>
            </div>
        </div>

        {{-- Card do aluno --}}
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;background:linear-gradient(135deg,#4361ee,#3a0ca3);border-radius:14px;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-person-fill text-white fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-800 mb-0">{{ $aluno->nome }}</h4>
                        @if($aluno->matricula)
                        <span class="text-muted small"><i class="bi bi-hash me-1"></i>{{ $aluno->matricula }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Notas dos bimestres --}}
        <div class="card mb-4">
            <div class="card-header bg-light fw-700">
                <i class="bi bi-list-ol me-2 text-primary"></i>Notas por Bimestre
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    @foreach([1,2,3,4] as $b)
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light">
                            <div class="small text-muted fw-700">{{ $b }}º Bimestre</div>
                            <div class="fw-800 fs-3 text-primary">{{ number_format($nota->{'bimestre'.$b}, 1) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RESULTADO PRINCIPAL --}}
        <div class="resultado-card {{ $nota->conceito }} p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="small text-muted fw-700 text-uppercase mb-1">Média Final</div>
                    <div class="media-destaque text-dark mb-2">{{ number_format($nota->media, 2) }}</div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $nota->conceitoBadgeClass }} badge-conceito me-2">{{ $nota->conceito }}</span>
                        <span class="fw-700 fs-5">
                            @switch($nota->conceito)
                                @case('A') <span class="text-success">Aprovado com Louvor</span> @break
                                @case('B') <span class="text-primary">Aluno Aprovado</span> @break
                                @case('C') <span class="text-warning">Recuperação, sua chance de passar</span> @break
                                @case('D') <span class="text-danger">Poxa vida, vamos tentar novamente ano que vem</span> @break
                            @endswitch
                        </span>
                    </div>

                    {{-- Ícone da situação --}}
                    <div class="fs-5">
                        @switch($nota->conceito)
                            @case('A') <i class="bi bi-trophy-fill text-success me-2"></i><span class="text-success fw-700">Excelente desempenho!</span> @break
                            @case('B') <i class="bi bi-check-circle-fill text-primary me-2"></i><span class="text-primary fw-700">Bom trabalho!</span> @break
                            @case('C') <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i><span class="text-warning fw-700">Atenção: recuperação necessária</span> @break
                            @case('D') <i class="bi bi-x-circle-fill text-danger me-2"></i><span class="text-danger fw-700">Reprovado</span> @break
                        @endswitch
                    </div>
                </div>
                <div class="col-md-4 text-center mt-3 mt-md-0">
                    <div style="font-size:5rem">
                        @switch($nota->conceito)
                            @case('A') 🏆 @break
                            @case('B') ✅ @break
                            @case('C') ⚠️ @break
                            @case('D') 😔 @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        {{-- RECUPERAÇÃO --}}
        @if($nota->conceito === 'C')
        <div class="card mb-4 border-warning border-2">
            <div class="card-header bg-warning text-dark fw-700">
                <i class="bi bi-arrow-repeat me-2"></i>Prova de Recuperação
            </div>
            <div class="card-body p-4">

                @if(!$nota->recuperacaoRealizada())
                    {{-- Ainda não fez recuperação --}}
                    <div class="alert alert-warning d-flex gap-2 align-items-start">
                        <i class="bi bi-info-circle-fill fs-5 mt-1"></i>
                        <div>
                            <strong>Este aluno está em recuperação.</strong><br>
                            Para ser aprovado, a soma da <strong>média bimestral ({{ number_format($nota->media, 2) }})</strong> com a <strong>nota da recuperação</strong> deve ser <strong>≥ 10,0</strong>.<br>
                            <small class="text-muted">Nota mínima necessária na recuperação: <strong>{{ number_format(10 - $nota->media, 1) }}</strong></small>
                        </div>
                    </div>

                    @if(!$aluno->turma->isFechada())
                    <a href="{{ route('notas.recuperacao.form', $aluno) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-2"></i>Lançar Nota da Recuperação
                    </a>
                    @endif

                @else
                    {{-- Recuperação já realizada --}}
                    <div class="row g-4 text-center">
                        <div class="col-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="small text-muted fw-700">Média Bimestral</div>
                                <div class="fw-800 fs-3">{{ number_format($nota->media, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="small text-muted fw-700">Nota Recuperação</div>
                                <div class="fw-800 fs-3">{{ number_format($nota->nota_recuperacao, 1) }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded-3 {{ $nota->aprovado_recuperacao ? 'bg-success' : 'bg-danger' }} bg-opacity-10">
                                <div class="small fw-700 {{ $nota->aprovado_recuperacao ? 'text-success' : 'text-danger' }}">Soma Total</div>
                                <div class="fw-800 fs-3 {{ $nota->aprovado_recuperacao ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($nota->media + $nota->nota_recuperacao, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        @if($nota->aprovado_recuperacao)
                            <div class="alert alert-success d-flex gap-2 align-items-center mb-0">
                                <i class="bi bi-check-circle-fill fs-4"></i>
                                <div>
                                    <strong>✅ Aprovado na Recuperação!</strong><br>
                                    <small>{{ number_format($nota->media, 2) }} + {{ number_format($nota->nota_recuperacao, 1) }} = {{ number_format($nota->media + $nota->nota_recuperacao, 1) }} ≥ 10,0</small>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger d-flex gap-2 align-items-center mb-0">
                                <i class="bi bi-x-circle-fill fs-4"></i>
                                <div>
                                    <strong>❌ Reprovado na Recuperação.</strong><br>
                                    <small>{{ number_format($nota->media, 2) }} + {{ number_format($nota->nota_recuperacao, 1) }} = {{ number_format($nota->media + $nota->nota_recuperacao, 1) }} &lt; 10,0</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!$aluno->turma->isFechada())
                    <a href="{{ route('notas.recuperacao.form', $aluno) }}" class="btn btn-outline-warning mt-3 btn-sm">
                        <i class="bi bi-pencil me-1"></i>Editar Nota de Recuperação
                    </a>
                    @endif
                @endif
            </div>
        </div>
        @endif

        {{-- Ações --}}
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('turmas.show', $aluno->turma) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Voltar para a Turma
            </a>
            @if(!$aluno->turma->isFechada())
            <a href="{{ route('notas.edit', $aluno) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Editar Notas
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
