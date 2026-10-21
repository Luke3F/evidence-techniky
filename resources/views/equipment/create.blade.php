@extends('layouts.app')
@section('content')
<div class="card">
    <h1>Nové zařízení</h1>
    <form method="POST" action="{{ route('equipment.store') }}">@include('equipment._form')</form>
</div>
@endsection
