<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Calculadora de Médias') — EduNota</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:     #4361ee;
            --primary-dark:#3a0ca3;
            --success:     #2dc653;
            --warning:     #f4a261;
            --danger:      #e63946;
            --sidebar-bg:  #e63946;
            --sidebar-text:#c0c7e8;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: #f0f2fa;
            min-height: 100vh;
        }

        /* ── Navbar ── */
        .navbar-brand span { color: #000; }
        .navbar { background: var(--sidebar-bg) !important; }
        .navbar .nav-link { color: var(--sidebar-text) !important; font-weight: 600; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: #fff !important; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(67,97,238,.08);
        }
        .card-header {
            border-radius: 16px 16px 0 0 !important;
            border-bottom: none;
            font-weight: 700;
            font-size: 1.05rem;
        }

        /* ── Badges de conceito ── */
        .badge-conceito {
            font-size: 1.5rem;
            font-weight: 800;
            width: 52px; height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        /* ── Resultado card ── */
        .resultado-card {
            border-left: 6px solid;
            border-radius: 12px;
        }
        .resultado-card.A { border-color: var(--success);  background: #f0fff4; }
        .resultado-card.B { border-color: var(--primary);  background: #f0f4ff; }
        .resultado-card.C { border-color: var(--warning);  background: #fff8f0; }
        .resultado-card.D { border-color: var(--danger);   background: #fff0f0; }

        /* ── Media destaque ── */
        .media-destaque {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1;
        }

        /* ── Turma fechada banner ── */
        .turma-fechada-banner {
            background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
            color: #fff;
            border-radius: 12px;
            padding: .75rem 1.25rem;
            font-weight: 700;
        }

        /* ── Table ── */
        .table th { font-weight: 700; font-size: .85rem; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; }

        /* ── Bimestre inputs ── */
        .nota-input { font-size: 1.15rem; font-weight: 700; text-align: center; }

        /* ── Botões ── */
        .btn { border-radius: 10px; font-weight: 700; }
        .btn-sm { font-size: .8rem; }

        /* ── Stats card ── */
        .stat-card { border-radius: 14px; padding: 1rem 1.5rem; color: #fff; }
        .stat-card .stat-number { font-size: 2.2rem; font-weight: 800; }
        .stat-card .stat-label  { font-size: .8rem; opacity: .85; text-transform: uppercase; letter-spacing: .06em; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-800 fs-4" href="{{ route('turmas.index') }}">
            <i class="bi bi-mortarboard-fill me-2" style="color:#000"></i>
            Unipar<span>Notas</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('turmas.*') ? 'active' : '' }}" href="{{ route('turmas.index') }}">
                        <i class="bi bi-people-fill me-1"></i>Turmas
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">

    {{-- Flash messages --}}
    @foreach(['success'=>'success','error'=>'danger','info'=>'info','warning'=>'warning'] as $type => $class)
        @if(session($type))
            <div class="alert alert-{{ $class }} alert-dismissible fade show rounded-3 mb-3" role="alert">
                <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : 'info-circle') }}-fill me-2"></i>
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    @yield('content')
</div>

<footer class="text-center text-muted py-4" style="font-size:.82rem">
    Desenvolvido por Vinicius Cordeiro &copy; {{ date('Y') }} — Calculadora de Médias Escolares
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
