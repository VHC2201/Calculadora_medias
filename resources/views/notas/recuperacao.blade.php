@extends('layouts.app')
@section('title', 'Recuperação — ' . $aluno->nome)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('notas.resultado', $aluno) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Prova de Recuperação</h2>
                <small class="text-muted">
                    <i class="bi bi-person-circle me-1"></i>{{ $aluno->nome }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-people me-1"></i>{{ $aluno->turma->nome }}
                </small>
            </div>
        </div>

        {{-- Resumo da situação --}}
        <div class="card border-warning border-2 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-700 text-warning mb-3"><i class="bi bi-info-circle me-2"></i>Situação do Aluno</h5>

                <div class="row g-3 text-center mb-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted fw-700">Média Bimestral</div>
                            <div class="fw-800 fs-2 text-warning">{{ number_format($nota->media, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted fw-700">Nota Mínima na Rec.</div>
                            <div class="fw-800 fs-2 text-primary">{{ number_format(10 - $nota->media, 1) }}</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning mb-0 d-flex gap-2 align-items-start">
                    <i class="bi bi-calculator-fill fs-5 mt-1"></i>
                    <div>
                        <strong>Regra de aprovação:</strong>
                        Média Bimestral <strong>({{ number_format($nota->media, 2) }})</strong> + Nota Recuperação <strong>≥ 10,0</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulário de recuperação --}}
        <div class="card">
            <div class="card-header bg-warning text-dark fw-700">
                <i class="bi bi-pencil-square me-2"></i>
                {{ $nota->recuperacaoRealizada() ? 'Editar Nota de Recuperação' : 'Lançar Nota de Recuperação' }}
            </div>
            <div class="card-body p-4">
                <form action="{{ route('notas.recuperacao.store', $aluno) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-700 fs-5">Nota da Recuperação <span class="text-danger">*</span></label>
                        <input type="number"
                               name="nota_recuperacao"
                               id="notaRec"
                               class="form-control nota-input form-control-lg @error('nota_recuperacao') is-invalid @enderror"
                               value="{{ old('nota_recuperacao', $nota->nota_recuperacao) }}"
                               placeholder="0.0"
                               min="0" max="10" step="0.1"
                               style="max-width:200px">
                        @error('nota_recuperacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Preview do resultado da recuperação --}}
                    <div id="previewRec" class="card mb-4 d-none">
                        <div class="card-body text-center py-3" id="previewRecBody">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-5">
                            <i class="bi bi-check-lg me-1"></i>Confirmar Recuperação
                        </button>
                        <a href="{{ route('notas.resultado', $aluno) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mediaBimestral = {{ $nota->media }};
    const input          = document.getElementById('notaRec');
    const preview        = document.getElementById('previewRec');
    const previewBody    = document.getElementById('previewRecBody');

    input.addEventListener('input', function () {
        const val = parseFloat(this.value);
        if (isNaN(val)) { preview.classList.add('d-none'); return; }

        const soma    = mediaBimestral + val;
        const aprovado = soma >= 10;

        previewBody.innerHTML = `
            <div class="row g-2 justify-content-center align-items-center">
                <div class="col-auto text-muted">${mediaBimestral.toFixed(2)} + ${val.toFixed(1)} =</div>
                <div class="col-auto fw-800 fs-3 ${aprovado ? 'text-success' : 'text-danger'}">${soma.toFixed(1)}</div>
                <div class="col-auto">
                    <span class="badge ${aprovado ? 'bg-success' : 'bg-danger'} fs-6">
                        ${aprovado ? '✅ Aprovado' : '❌ Reprovado'}
                    </span>
                </div>
            </div>`;

        preview.className = `card mb-4 border-2 ${aprovado ? 'border-success' : 'border-danger'}`;
    });

    // Trigger on load if value already present
    if (input.value) input.dispatchEvent(new Event('input'));
</script>
@endpush
