<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id', 'station_id'
    ];

    protected $casts = [
        'request_id' => 'integer',
        'station_id' => 'integer'
    ];
}
