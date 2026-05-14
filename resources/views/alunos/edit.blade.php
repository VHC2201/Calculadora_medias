@extends('layouts.app')
@section('title', 'Editar Aluno')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('turmas.show', $aluno->turma) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Editar Aluno</h2>
                <small class="text-muted">{{ $aluno->nome }}</small>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-pencil-fill me-2"></i>Editar Dados
            </div>
            <div class="card-body p-4">
                <form action="{{ route('alunos.update', $aluno) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-700">Nome Completo <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control form-control-lg @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $aluno->nome) }}">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-700">Matrícula</label>
                        <input type="text" name="matricula" class="form-control"
                               value="{{ old('matricula', $aluno->matricula) }}">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-5">
                            <i class="bi bi-check-lg me-1"></i>Salvar
                        </button>
                        <a href="{{ route('turmas.show', $aluno->turma) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
