<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_session_id',
        'product_id',
        'live_price',
    ];

    protected function casts(): array
    {
        return [
            'live_price' => 'decimal:2',
        ];
    }

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(
            LiveSession::class
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class
        );
    }
}
