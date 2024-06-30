<?php

namespace App\Models\User;

use App\Models\Phone;
use App\Models\Request\Request;
use App\Models\User\Workday\Workday;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    const SEX_FEMALE = 0;
    const SEX_MALE = 1;

    protected $fillable = [
        'name', 'lastname', 'patronymic',
        'sex', 'number',
        'light_work',
        'work_phone_id', 'personal_phone_id',
        'shift_id', 'position_id',
        'area_id', 'workday_id'
    ];

    protected $casts = [
        'sex' => 'boolean',
        'light_work' => 'boolean',
        'work_phone_id' => 'integer',
        'personal_phone_id' => 'integer',
        'shift_id' => 'integer',
        'position_id' => 'integer',
        'area_id' => 'integer',
        'workday_id' => 'integer',
    ];    

    protected $appends = ['fio', 'fio_short'];
    
    private static function getGenders(): array
    {
        return [
            self::SEX_MALE => 'Мужской',
            self::SEX_FEMALE => 'Женский'
        ];
    }

    public function getGenderTitleAttribute(): ?string
    {
        return self::getGenders()[$this->gender] ?? null;
    }

    public function getFioAttribute(): string
    {
        $fio = $this->lastname . ' ' . $this->name;
        if ($this->patronymic) $fio .= ' ' . $this->patronymic;
        return $fio;
    }

    public function getFioShortAttribute(): string
    {
        $name = mb_substr($this->name, 0, 1) . '.';
        $fio = $this->lastname . ' ' . $name;
        if ($this->patronymic) $fio .= mb_substr($this->patronymic, 0, 1) . '.';
        return $fio;
    }

    public function workPhone(): BelongsTo
    {
        return $this->belongsTo(Phone::class, 'work_phone_id');
    }

    public function personalPhone(): BelongsTo
    {
        return $this->belongsTo(Phone::class, 'personal_phone_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function workday(): BelongsTo
    {
        return $this->belongsTo(Workday::class);
    }

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Request::class, 'employee_requests');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }        
}
