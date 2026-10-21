@extends('layouts.app')
@section('content')
<div class="card">
    <div class="row">
        <h1>Zařízení</h1>
        <a class="btn" href="{{ route('equipment.create') }}">+ Nové zařízení</a>
    </div>
    <p class="filters">Filtr:
        <a href="{{ route('equipment.index') }}" class="{{ !$status ? 'active' : '' }}">Vše</a>
        @foreach (\App\Models\Equipment::STATUSES as $key => $s)
            <a href="{{ route('equipment.index', ['status' => $key]) }}" class="{{ $status === $key ? 'active' : '' }}">{{ $s['label'] }}</a>
        @endforeach
    </p>
    <table>
        <thead><tr><th>Ev. číslo</th><th>Název</th><th>Stav</th><th>Nákup</th><th class="num">Náklady na servis</th><th></th></tr></thead>
        <tbody>
        @forelse ($equipment as $e)
            <tr>
                <td>{{ $e->inventory_number }}</td>
                <td><a href="{{ route('equipment.show', $e) }}">{{ $e->name }}</a></td>
                <td><span class="badge {{ $e->status_class }}">{{ $e->status_label }}</span></td>
                <td>{{ $e->purchased_at->format('j. n. Y') }}</td>
                <td class="num">{{ number_format($e->total_cost ?? 0, 2, ',', ' ') }} Kč</td>
                <td class="num">
                    <a class="btn small secondary" href="{{ route('equipment.edit', $e) }}">Upravit</a>
                    <form class="inline" method="POST" action="{{ route('equipment.destroy', $e) }}" onsubmit="return confirm('Opravdu smazat včetně servisní historie?')">
                        @csrf @method('DELETE')
                        <button class="btn small danger">Smazat</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Žádná zařízení.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $equipment->links() }}</div>
</div>
@endsection
