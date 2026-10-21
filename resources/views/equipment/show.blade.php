@extends('layouts.app')
@section('title', $equipment->name)
@section('content')
<div class="card">
    <div class="row">
        <h1>{{ $equipment->name }} <span class="badge {{ $equipment->status_class }}">{{ $equipment->status_label }}</span></h1>
        <div>
            <a class="btn secondary" href="{{ route('equipment.edit', $equipment) }}">Upravit</a>
            <a class="btn secondary" href="{{ route('equipment.index') }}">Seznam</a>
        </div>
    </div>
    <p>Evidenční číslo: <strong>{{ $equipment->inventory_number }}</strong><br>
       Datum nákupu: {{ $equipment->purchased_at->format('j. n. Y') }}</p>
</div>

<div class="card">
    <div class="row">
        <h2>Servisní historie</h2>
        <a class="btn small" href="{{ route('equipment.export', $equipment) }}">Export CSV</a>
    </div>
    <table>
        <thead><tr><th>Datum</th><th>Popis</th><th class="num">Cena</th><th></th></tr></thead>
        <tbody>
        @forelse ($equipment->serviceRecords as $r)
            <tr>
                <td>{{ $r->serviced_at->format('j. n. Y') }}</td>
                <td>{{ $r->description }}</td>
                <td class="num">{{ number_format($r->cost, 2, ',', ' ') }} Kč</td>
                <td class="num">
                    <form class="inline" method="POST" action="{{ route('service-records.destroy', [$equipment, $r]) }}" onsubmit="return confirm('Smazat záznam?')">
                        @csrf @method('DELETE')
                        <button class="btn small danger">Smazat</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Zatím žádné servisní zásahy.</td></tr>
        @endforelse
        </tbody>
        <tfoot><tr><th colspan="2" class="num">Celkové náklady:</th><th class="num">{{ number_format($totalCost, 2, ',', ' ') }} Kč</th><th></th></tr></tfoot>
    </table>
</div>

<div class="card">
    <h2>Přidat servisní záznam</h2>
    <form method="POST" action="{{ route('service-records.store', $equipment) }}">
        @csrf
        <label for="description">Popis</label>
        <textarea id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
        <label for="serviced_at">Datum servisu</label>
        <input id="serviced_at" type="date" name="serviced_at" value="{{ old('serviced_at', now()->format('Y-m-d')) }}" required>
        <label for="cost">Cena (Kč)</label>
        <input id="cost" name="cost" inputmode="decimal" value="{{ old('cost', '0') }}" required>
        <p><button class="btn">Přidat záznam</button></p>
    </form>
</div>
@endsection
