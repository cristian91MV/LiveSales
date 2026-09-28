<?php

namespace App\Models;

use App\Enums\LiveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'scheduled_at',
        'started_at',
        'ended_at',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'status' => LiveStatus::class,
        ];
    }

    public function liveProducts(): HasMany
    {
        return $this->hasMany(
            LiveProduct::class
        );
    }
}
