<?php

namespace App\Services;

use App\Models\Passenger\CategoryPassenger;
use App\Models\Passenger\Passenger;
use App\Models\Request\Luggage;
use App\Models\Request\LuggageType;
use App\Models\Request\Request;
use App\Models\Request\RequestMethod;
use App\Models\Request\RequestStatus;
use App\Models\Station\MetroStation;
use App\Models\User\Employee;
use Carbon\Carbon;
use DateInterval;
use DateTime;

class RequestService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Request::class);
    }

    public function distribution()
    {
        
    }

    public function createData(): array
    {
        $categories = CategoryPassenger::all('id', 'short_title');
        $stations = MetroStation::all('id', 'title');
        $methods = RequestMethod::all('id', 'title');
        $luggageTypes = LuggageType::all('id', 'title');
        $statuses = RequestStatus::all('id', 'title');

        $employees = Employee::all()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'fio_short' => $employee->fio_short,
            ];
        })->toArray();

        $passengers = Passenger::all()->map(function ($passenger) {
            return [
                'id' => $passenger->id,
                'category_id' => $passenger->category_id,
                'fio_short' => $passenger->fio_short,
            ];
        })->toArray();


        return [
            'categories' => $categories,
            'stations' => $stations,
            'methods' => $methods,
            'luggage_types' => $luggageTypes,
            'employees' => $employees,
            'passengers' => $passengers,
            'statuses' => $statuses,
        ];
    }

    public function create(array $data): Request
    {
        $data['passenger_id'] = $data['passenger']['id'];
        $data['status_id'] = $data['status']['id'];
        $data['station_departure_id'] = $data['station_departure']['id'];
        $data['station_arrival_id'] = $data['station_arrival']['id'];
        $data['method_id'] = $data['method']['id'];

        if ($data['category']) {
            $data['category_id'] = $data['category']['id'];
        }

        if ($data['luggage_type']) {
            $luggageTypeId = $this->setFieldId($data['luggage_type'], LuggageType::class);
            if ($weight = $data['luggage_weight']) {
                $data['luggage_id'] = Luggage::create([
                    'luggage_type_id' => $luggageTypeId,
                    'weight' => $weight,
                    'need_help' => $data['need_help']
                ])->id;
            }
        }

        $dateMeet = new DateTime($data['date_meet']);
        $timeStart = $dateMeet->format('H:i:s');
        $data['time_start'] = $timeStart;

        $totalTimeMinutes = $data['metroRouter']['total_time'];
        $hours = floor($totalTimeMinutes / 60);
        $minutes = $totalTimeMinutes % 60;
        $timeOver = sprintf('%02d:%02d:00', $hours, $minutes);
        $data['time_over'] = $timeOver;

        $timeOverInterval = new DateInterval(sprintf('PT%dH%dM', $hours, $minutes));
        $dateMeet->add($timeOverInterval);
        $data['time_end'] = $dateMeet->format('Y-m-d H:i:s');

        $transfers = $data['metroRouter']['transfers'];
        $transfersId = [];

        foreach ($transfers as $transfer) {
            $transfersId[] = $transfer['id'];
        }

        $request = Request::create($data);

        $request->transfers()->sync($transfersId);

        return $request;
    }
}
