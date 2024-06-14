<?php

namespace App\Models\User\Workday;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JoblessDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id', 'workday_id',
        'start', 'end'
    ];

    protected $casts = [
        'status_id' => 'integer',
        'workday_id' => 'integer',
        'start' => 'date',
        'end' => 'date'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(JoblessStatus::class, 'status_id');
    }

    public function workday(): BelongsTo
    {
        return $this->belongsTo(Workday::class);
    }
}
