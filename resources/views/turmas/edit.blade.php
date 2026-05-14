@extends('layouts.app')
@section('title', 'Editar Turma')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('turmas.show', $turma) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Editar Turma</h2>
                <small class="text-muted">{{ $turma->nome }}</small>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-pencil-fill me-2"></i>Editar Dados
            </div>
            <div class="card-body p-4">
                <form action="{{ route('turmas.update', $turma) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-700">Nome da Turma <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control form-control-lg @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $turma->nome) }}">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-700">Ano Letivo</label>
                            <input type="text" name="ano" class="form-control"
                                   value="{{ old('ano', $turma->ano) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-700">Período</label>
                            <select name="periodo" class="form-select">
                                <option value="">— Selecione —</option>
                                @foreach(['Manhã','Tarde','Noite','Integral'] as $p)
                                <option value="{{ $p }}" {{ old('periodo', $turma->periodo) == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-5">
                            <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                        </button>
                        <a href="{{ route('turmas.show', $turma) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
