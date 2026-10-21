<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    public const STATUSES = [
        'active'  => ['label' => 'Aktivní',   'class' => 'green'],
        'service' => ['label' => 'V servisu', 'class' => 'orange'],
        'retired' => ['label' => 'Vyřazeno',  'class' => 'grey'],
    ];

    protected $table = 'equipment';

    protected $fillable = ['name', 'inventory_number', 'status', 'purchased_at'];

    protected $casts = ['purchased_at' => 'date'];

    public function serviceRecords(): HasMany
    {
        return $this->hasMany(ServiceRecord::class)->orderByDesc('serviced_at');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? $this->status;
    }

    public function getStatusClassAttribute(): string
    {
        return self::STATUSES[$this->status]['class'] ?? 'grey';
    }
}
