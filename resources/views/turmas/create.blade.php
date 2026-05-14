@extends('layouts.app')
@section('title', 'Nova Turma')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('turmas.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Nova Turma</h2>
                <small class="text-muted">Preencha os dados da turma</small>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-people-fill me-2"></i>Dados da Turma
            </div>
            <div class="card-body p-4">
                <form action="{{ route('turmas.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-700">Nome da Turma <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control form-control-lg @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" placeholder="Ex: 3º Ano A, Turma 201...">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-700">Ano Letivo</label>
                            <input type="text" name="ano" class="form-control @error('ano') is-invalid @enderror"
                                   value="{{ old('ano', date('Y')) }}" placeholder="2025">
                            @error('ano')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-700">Período</label>
                            <select name="periodo" class="form-select @error('periodo') is-invalid @enderror">
                                <option value="">— Selecione —</option>
                                <option value="Manhã"   {{ old('periodo') == 'Manhã'   ? 'selected' : '' }}>Manhã</option>
                                <option value="Tarde"   {{ old('periodo') == 'Tarde'   ? 'selected' : '' }}>Tarde</option>
                                <option value="Noite"   {{ old('periodo') == 'Noite'   ? 'selected' : '' }}>Noite</option>
                                <option value="Integral" {{ old('periodo') == 'Integral' ? 'selected' : '' }}>Integral</option>
                            </select>
                            @error('periodo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-lg me-1"></i>Cadastrar Turma
                        </button>
                        <a href="{{ route('turmas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
