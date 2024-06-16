<?php

namespace App\Models\Station;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'st1_id', 'st2_id', 'time'
    ];

    protected $casts = [
        'st1_id' => 'integer',
        'st2_id' => 'integer',
        'time' => 'float'
    ];

    public function stationFirst(): BelongsTo
    {
        return $this->belongsTo(MetroStation::class, 'st1_id');
    }

    public function stationSecond(): BelongsTo
    {
        return $this->belongsTo(MetroStation::class, 'st2_id');
    }
}
