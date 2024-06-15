<?php

namespace App\Models\Station;

use App\Models\Request\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetroStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'station_line_id'
    ];

    protected $casts = [
        'station_line_id' => 'integer'
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(StationLine::class);
    }

    public function requestsDeparture(): HasMany
    {
        return $this->hasMany(Request::class, 'station_departure_id');
    }

    public function requestsArrival(): HasMany
    {
        return $this->hasMany(Request::class, 'station_arrival_id');
    }
}
