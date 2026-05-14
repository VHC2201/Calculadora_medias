@extends('layouts.app')
@section('title', 'Lançar Notas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('turmas.show', $aluno->turma) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Lançar Notas</h2>
                <small class="text-muted">
                    <i class="bi bi-person-circle me-1"></i>{{ $aluno->nome }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-people me-1"></i>{{ $aluno->turma->nome }}
                </small>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-pencil-square me-2"></i>Notas Bimestrais
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">Informe as notas de cada bimestre (de <strong>0,0</strong> a <strong>10,0</strong>).</p>

                <form action="{{ route('notas.store', $aluno) }}" method="POST" id="formNotas">
                    @csrf

                    <div class="row g-4 mb-4">
                        @foreach([1,2,3,4] as $b)
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-700 text-center d-block">{{ $b }}º Bimestre</label>
                            <input type="number"
                                   name="bimestre{{ $b }}"
                                   id="b{{ $b }}"
                                   class="form-control nota-input @error('bimestre'.$b) is-invalid @enderror"
                                   value="{{ old('bimestre'.$b) }}"
                                   placeholder="0.0"
                                   min="0" max="10" step="0.1">
                            @error('bimestre'.$b)
                                <div class="invalid-feedback text-center">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>

                    {{-- Preview da média em tempo real --}}
                    <div class="alert alert-light border-2 border-primary d-flex align-items-center gap-3 mb-4" id="previewMedia" style="display:none!important">
                        <i class="bi bi-calculator-fill fs-4 text-primary"></i>
                        <div>
                            <div class="small text-muted">Média estimada</div>
                            <div class="fw-800 fs-4 text-primary" id="mediaValor">—</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-lg me-1"></i>Calcular e Salvar
                        </button>
                        <a href="{{ route('turmas.show', $aluno->turma) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Legenda dos conceitos --}}
        <div class="card mt-4">
            <div class="card-header bg-light">
                <i class="bi bi-info-circle me-2 text-primary"></i><strong>Tabela de Conceitos</strong>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success">
                            <span class="badge bg-success fs-5 mb-1">A</span>
                            <div class="small fw-700">Média &gt; 9,0</div>
                            <div class="small text-muted">Aprovado com Louvor</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary">
                            <span class="badge bg-primary fs-5 mb-1">B</span>
                            <div class="small fw-700">Média &gt; 7,0</div>
                            <div class="small text-muted">Aluno Aprovado</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning">
                            <span class="badge bg-warning text-dark fs-5 mb-1">C</span>
                            <div class="small fw-700">Média &gt; 4,0</div>
                            <div class="small text-muted">Recuperação</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger">
                            <span class="badge bg-danger fs-5 mb-1">D</span>
                            <div class="small fw-700">Média ≤ 4,0</div>
                            <div class="small text-muted">Reprovado</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const inputs = ['b1','b2','b3','b4'].map(id => document.getElementById(id));
    const preview = document.getElementById('previewMedia');
    const mediaValor = document.getElementById('mediaValor');

    function calcularPreview() {
        const vals = inputs.map(i => parseFloat(i.value));
        const todos = vals.every(v => !isNaN(v));
        if (todos) {
            const media = vals.reduce((a, b) => a + b, 0) / 4;
            mediaValor.textContent = media.toFixed(2);
            preview.style.display = 'flex';
        } else {
            preview.style.display = 'none';
        }
    }

    inputs.forEach(i => i.addEventListener('input', calcularPreview));
</script>
@endpush
