<?php

namespace App\Models\Passenger;

use App\Models\Phone;
use App\Models\Request\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passenger extends Model
{
    use HasFactory;

    const SEX_FEMALE = 0;
    const SEX_MALE = 1;

    protected $fillable = [
        'id', 'name', 'lastname', 'patronymic',
        'sex',  'category_id',
        'comment', 'CP'
    ];

    protected $casts = [
        'sex' => 'boolean',
        'category_id' => 'integer',
        'CP' => 'boolean'
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryPassenger::class, 'category_id');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }    
}
