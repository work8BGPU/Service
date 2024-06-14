<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'request_id'
    ];

    protected $casts = [
        'employee_id' => 'integer',
        'request_id' => 'integer'
    ];
}
