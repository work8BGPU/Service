<?php

namespace App\Models\Station;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StationLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'title'
    ];

    public function stations(): HasMany
    {
        return $this->hasMany(MetroStation::class);
    }
}
