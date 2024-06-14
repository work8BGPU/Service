<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Luggage extends Model
{
    use HasFactory;

    protected $fillable = [
        'weight', 'need_help',
        'luggage_type_id'
    ];

    protected $casts = [
        'weight' => 'float',
        'need_help' => 'boolean',
        'luggage_type_id' => 'integer'
    ];

    public function luggageType(): BelongsTo
    {
        return $this->belongsTo(LuggageType::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
