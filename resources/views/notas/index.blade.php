@extends('layouts.app')
@section('title', 'Notas — ' . $aluno->nome)

@section('content')
    @if($nota)
        @php return redirect()->route('notas.resultado', $aluno); @endphp
    @else
        @php return redirect()->route('notas.create', $aluno); @endphp
    @endif
@endsection
