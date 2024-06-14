<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LuggageType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function luggage(): HasMany
    {
        return $this->hasMany(Luggage::class);
    }
}
