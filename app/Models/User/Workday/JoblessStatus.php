<?php

namespace App\Models\User\Workday;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JoblessStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function joblessDays(): HasMany
    {
        return $this->hasMany(JoblessDay::class, 'status_id');
    }
}
