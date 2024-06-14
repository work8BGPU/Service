<?php

namespace App\Models;

use App\Models\Passenger\Passenger;
use App\Models\User\Employee;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 'description',
        'passenger_id'
    ];

    protected $casts = [
        'passenger_id' => 'integer'
    ];

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function employeesWork(): HasMany
    {
        return $this->hasMany(Employee::class, 'work_phone_id');
    }

    public function employeePersonal(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'personal_phone_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
