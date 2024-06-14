<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class, 'method_id');
    }
}
