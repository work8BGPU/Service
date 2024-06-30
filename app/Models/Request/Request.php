<?php

namespace App\Models\Request;

use App\Models\Passenger\CategoryPassenger;
use App\Models\Station\MetroStation;
use App\Models\Passenger\Passenger;
use App\Models\Station\Station;
use App\Models\User\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'passenger_id',
        'station_departure_id', 'station_arrival_id',
        'description_departure', 'description_arrival',
        'date_meet',
        'time_start', 'time_end', 'time_over',
        'method_id', 'number_passenger',
        'category_id',
        'insp_m', 'insp_f',
        'luggage_id', 'status_id',
        'information',
        'created_at'
    ];

    protected $casts = [
        'passenger_id' => 'integer',
        'station_departure_id' => 'integer',
        'station_arrival_id' => 'integer',
        'date_meet' => 'datetime',
        'time_start' => 'datetime:H:i:s',
        'time_end' => 'datetime:H:i:s',
        'time_over' => 'datetime:H:i:s',
        'method_id' => 'integer',
        'number_passenger' => 'integer',
        'insp_m' => 'integer',
        'insp_f' => 'integer',
        'luggage_id' => 'integer',
        'status_id' => 'integer',
        'category_id' => 'integer'
    ];

    public function getTimeStartAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    public function getTimeEndAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryPassenger::class, 'category_id');
    }

    public function stationDeparture(): BelongsTo
    {
        return $this->belongsTo(MetroStation::class, 'station_departure_id');
    }

    public function stationArrival(): BelongsTo
    {
        return $this->belongsTo(MetroStation::class, 'station_arrival_id');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(RequestMethod::class, 'method_id');
    }

    public function luggage(): BelongsTo
    {
        return $this->belongsTo(Luggage::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class, 'status_id');
    }

    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class);
    }

    public function transfers(): BelongsToMany
    {
        return $this->belongsToMany(MetroStation::class, 'transfers', 'request_id', 'station_id');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_requests');
    }
}
