@extends('layouts.app')
@section('title', 'Turmas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-800 mb-0"><i class="bi bi-people-fill me-2 text-primary"></i>Turmas</h2>
        <small class="text-muted">Gerencie suas turmas e alunos</small>
    </div>
    <a href="{{ route('turmas.create') }}" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-1"></i> Nova Turma
    </a>
</div>

@if($turmas->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="bi bi-people display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">Nenhuma turma cadastrada</h4>
            <a href="{{ route('turmas.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-lg me-1"></i> Cadastrar primeira turma
            </a>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($turmas as $turma)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 {{ $turma->isFechada() ? 'border border-danger border-2' : '' }}">
                <div class="card-header {{ $turma->isFechada() ? 'bg-danger text-white' : 'bg-primary text-white' }} d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-{{ $turma->isFechada() ? 'lock-fill' : 'people-fill' }} me-2"></i>{{ $turma->nome }}</span>
                    @if($turma->isFechada())
                        <span class="badge bg-white text-danger fw-700">FECHADA</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3 mb-3">
                        @if($turma->ano)
                        <span class="badge bg-light text-dark border"><i class="bi bi-calendar3 me-1"></i>{{ $turma->ano }}</span>
                        @endif
                        @if($turma->periodo)
                        <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i>{{ $turma->periodo }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-card flex-fill text-center" style="background:linear-gradient(135deg,#4361ee,#3a0ca3)">
                            <div class="stat-number">{{ $turma->alunos_count }}</div>
                            <div class="stat-label">Alunos</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <a href="{{ route('turmas.show', $turma) }}" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-eye me-1"></i>Ver Turma
                    </a>
                    @if(!$turma->isFechada())
                    <a href="{{ route('turmas.edit', $turma) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('turmas.destroy', $turma) }}" method="POST" onsubmit="return confirm('Remover esta turma e todos os alunos?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
