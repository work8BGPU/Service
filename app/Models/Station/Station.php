<?php

namespace App\Models;

use App\Models\Request\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Request::class);
    }
}
