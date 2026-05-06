@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Proyecto #{{ $project->id }}</h2>

    <p><strong>Nombre:</strong> {{ $project->name }}</p>
    <p><strong>Estado:</strong> {{ $project->status }}</p>

    <h3>Total estimado: ${{ number_format($total ?? 0, 2) }}</h3>

    <hr>

    <h4>Códigos</h4>
    <pre>{{ print_r($data['codes'] ?? [], true) }}</pre>

    <h4>Módulos</h4>
    <pre>{{ print_r($data['modules'] ?? [], true) }}</pre>

    <h4>Elementos</h4>
    <pre>{{ print_r($data['elements'] ?? [], true) }}</pre>

    <h4>Otros</h4>
    <pre>{{ print_r($data['others'] ?? [], true) }}</pre>
</div>
@endsection