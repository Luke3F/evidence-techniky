<?php

namespace App\Http\Requests;

use App\Models\Equipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Při editaci ignorujeme vlastní záznam v kontrole unikátnosti
        $current = $this->route('equipment');

        return [
            'name' => ['required', 'string', 'max:255'],
            'inventory_number' => [
                'required', 'string', 'max:50',
                Rule::unique('equipment', 'inventory_number')->ignore($current?->id),
            ],
            'status' => ['required', Rule::in(array_keys(Equipment::STATUSES))],
            'purchased_at' => ['required', 'date_format:Y-m-d', 'before_or_equal:today', 'after:1900-01-01'],
        ];
    }

    public function messages(): array
    {
        return [
            'inventory_number.unique' => 'Toto evidenční číslo už existuje.',
            'status.in' => 'Neplatný stav zařízení.',
            'purchased_at.date_format' => 'Datum nákupu musí být ve formátu RRRR-MM-DD.',
            'purchased_at.before_or_equal' => 'Datum nákupu nesmí být v budoucnosti.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'název',
            'inventory_number' => 'evidenční číslo',
            'status' => 'stav',
            'purchased_at' => 'datum nákupu',
        ];
    }
}
