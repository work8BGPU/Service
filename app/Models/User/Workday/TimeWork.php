<?php

namespace App\Models\User\Workday;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'time'
    ];

    public function workdays(): HasMany
    {
        return $this->hasMany(Workday::class);
    }

    public function workdaysNewTime(): HasMany
    {
        return $this->hasMany(Workday::class, 'new_time_work_id');
    }
}
