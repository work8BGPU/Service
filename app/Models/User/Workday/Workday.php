<?php

namespace App\Models\User\Workday;

use App\Models\User\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workday extends Model
{
    use HasFactory;

    protected $fillable = [
        'employment_date',
        'time_work_id',
        'extra_shift',
        'new_time_work_date',
        'new_time_work_id',
        'intern'
    ];

    protected $casts = [
        'employment_date' => 'date',
        'time_work_id' => 'integer',
        'extra_shift' => 'date',
        'new_time_work_date' => 'date',
        'new_time_work_id' => 'integer',
        'intern' => 'boolean'
    ];

    public function timeWork(): BelongsTo
    {
        return $this->belongsTo(TimeWork::class);
    }

    public function newTimeWork(): BelongsTo
    {
        return $this->belongsTo(TimeWork::class, 'new_time_work_id');
    }

    public function joblessDays(): HasMany
    {
        return $this->hasMany(JoblessDay::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
