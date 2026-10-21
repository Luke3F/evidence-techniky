@extends('layouts.app')
@section('content')
<div class="card">
    <h1>Upravit: {{ $equipment->name }}</h1>
    <form method="POST" action="{{ route('equipment.update', $equipment) }}">@method('PUT') @include('equipment._form')</form>
</div>
@endsection
