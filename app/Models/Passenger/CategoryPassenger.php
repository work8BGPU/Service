<?php

namespace App\Models\Passenger;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'title', 'short_title',
        'description'
    ];

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class, 'category_id');
    }
}
