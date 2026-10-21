@csrf
<label for="name">Název</label>
<input id="name" name="name" value="{{ old('name', $equipment->name) }}" required>
@error('name') <div class="error">{{ $message }}</div> @enderror

<label for="inventory_number">Evidenční číslo</label>
<input id="inventory_number" name="inventory_number" value="{{ old('inventory_number', $equipment->inventory_number) }}" required>
@error('inventory_number') <div class="error">{{ $message }}</div> @enderror

<label for="status">Stav</label>
<select id="status" name="status" required>
    @foreach (\App\Models\Equipment::STATUSES as $key => $s)
        <option value="{{ $key }}" @selected(old('status', $equipment->status) === $key)>{{ $s['label'] }}</option>
    @endforeach
</select>
@error('status') <div class="error">{{ $message }}</div> @enderror

<label for="purchased_at">Datum nákupu</label>
<input id="purchased_at" type="date" name="purchased_at" value="{{ old('purchased_at', $equipment->purchased_at?->format('Y-m-d')) }}" required>
@error('purchased_at') <div class="error">{{ $message }}</div> @enderror

<p style="margin-top:20px">
    <button class="btn">Uložit</button>
    <a class="btn secondary" href="{{ route('equipment.index') }}">Zpět</a>
</p>
