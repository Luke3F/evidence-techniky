<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $equipment = $this->route('equipment');

        return [
            'description' => ['required', 'string', 'max:2000'],
            'serviced_at' => [
                'required', 'date_format:Y-m-d', 'before_or_equal:today',
                'after_or_equal:' . $equipment->purchased_at->format('Y-m-d'),
            ],
            'cost' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'decimal:0,2'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Povolit desetinnou čárku (Čech zadá 1250,50)
        if ($this->has('cost') && is_string($this->cost)) {
            $this->merge(['cost' => str_replace(',', '.', trim($this->cost))]);
        }
    }

    public function messages(): array
    {
        return [
            'serviced_at.date_format' => 'Datum servisu musí být ve formátu RRRR-MM-DD.',
            'serviced_at.before_or_equal' => 'Datum servisu nesmí být v budoucnosti.',
            'serviced_at.after_or_equal' => 'Servis nemůže být dříve než nákup zařízení.',
            'cost.min' => 'Cena nesmí být záporná.',
            'cost.numeric' => 'Cena musí být číslo.',
            'cost.decimal' => 'Cena může mít nejvýše 2 desetinná místa.',
        ];
    }

    public function attributes(): array
    {
        return [
            'description' => 'popis',
            'serviced_at' => 'datum servisu',
            'cost' => 'cena',
        ];
    }
}
