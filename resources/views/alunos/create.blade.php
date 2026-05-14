@extends('layouts.app')
@section('title', 'Novo Aluno')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('turmas.show', $turma) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Novo Aluno</h2>
                <small class="text-muted">Turma: <strong>{{ $turma->nome }}</strong></small>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="bi bi-person-plus-fill me-2"></i>Dados do Aluno
            </div>
            <div class="card-body p-4">
                <form action="{{ route('turmas.alunos.store', $turma) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-700">Nome Completo <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control form-control-lg @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" placeholder="Nome do aluno...">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-700">Matrícula</label>
                        <input type="text" name="matricula" class="form-control @error('matricula') is-invalid @enderror"
                               value="{{ old('matricula') }}" placeholder="Número de matrícula (opcional)">
                        @error('matricula')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-5">
                            <i class="bi bi-check-lg me-1"></i>Cadastrar Aluno
                        </button>
                        <a href="{{ route('turmas.show', $turma) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
