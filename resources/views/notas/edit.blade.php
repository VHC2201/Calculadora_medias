@extends('layouts.app')
@section('title', 'Editar Notas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('notas.resultado', $aluno) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0">Editar Notas</h2>
                <small class="text-muted">
                    <i class="bi bi-person-circle me-1"></i>{{ $aluno->nome }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-people me-1"></i>{{ $aluno->turma->nome }}
                </small>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-pencil-square me-2"></i>Editar Notas Bimestrais
            </div>
            <div class="card-body p-4">
                <div class="alert alert-warning d-flex gap-2 align-items-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>Alterar as notas recalculará automaticamente a média, conceito e situação do aluno.</span>
                </div>

                <form action="{{ route('notas.update', $aluno) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-4 mb-4">
                        @foreach([1,2,3,4] as $b)
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-700 text-center d-block">{{ $b }}º Bimestre</label>
                            <input type="number"
                                   name="bimestre{{ $b }}"
                                   id="b{{ $b }}"
                                   class="form-control nota-input @error('bimestre'.$b) is-invalid @enderror"
                                   value="{{ old('bimestre'.$b, $nota->{'bimestre'.$b}) }}"
                                   min="0" max="10" step="0.1">
                            @error('bimestre'.$b)
                                <div class="invalid-feedback text-center">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>

                    {{-- Preview --}}
                    <div class="alert alert-light border-2 border-warning d-flex align-items-center gap-3 mb-4" id="previewMedia">
                        <i class="bi bi-calculator-fill fs-4 text-warning"></i>
                        <div>
                            <div class="small text-muted">Nova média estimada</div>
                            <div class="fw-800 fs-4 text-warning" id="mediaValor">
                                {{ number_format($nota->media, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-5">
                            <i class="bi bi-check-lg me-1"></i>Salvar Alterações
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
    const inputs = ['b1','b2','b3','b4'].map(id => document.getElementById(id));
    const mediaValor = document.getElementById('mediaValor');

    function calcularPreview() {
        const vals = inputs.map(i => parseFloat(i.value));
        const todos = vals.every(v => !isNaN(v));
        if (todos) {
            mediaValor.textContent = (vals.reduce((a, b) => a + b, 0) / 4).toFixed(2);
        }
    }

    inputs.forEach(i => i.addEventListener('input', calcularPreview));
</script>
@endpush
