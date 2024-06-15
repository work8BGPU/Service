<?php

namespace App\Services;

use App\Models\Phone;
use App\Models\User\Area;
use App\Models\User\Employee;
use App\Models\User\Position;
use App\Models\User\Shift;
use App\Models\User\Workday\TimeWork;

class EmployeeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Employee::class);
    }

    public function createData()
    {
        $positions = Position::all('id', 'title');
        $shifts = Shift::all('id', 'title');
        $areas = Area::all('id', 'title');

        return [
            'positions' => $positions,
            'shifts' => $shifts,
            'areas' => $areas
        ];
    }

    public function create(array $data): Employee
    {
        if (is_array($data['position'])) {
            $data['position_id'] = $data['position']['id'];
        } else {
            $data['position_id'] = Position::create(['title' => $data['position']])->id;
        }

        if (is_array($data['shift'])) {
            $data['shift_id'] = $data['shift']['id'];
        } else {
            $data['shift_id'] = Shift::create(['title' => $data['shift']])->id;
        }

        if (is_array($data['area'])) {
            $data['area_id'] = $data['area']['id'];
        } else {
            $data['area_id'] = Area::create(['title' => $data['area']])->id;
        }

        unset($data['position']);
        unset($data['shift']);
        unset($data['area']);

        if ($phone = Phone::where('phone', $data['personal_phone'])->first()) {
            $data['personal_phone_id'] = $phone->id;
        } else {
            $data['personal_phone_id'] = Phone::create(['phone' => $data['personal_phone']])->id;
        }

        if ($phone = Phone::where('phone', $data['work_phone'])->first()) {
            $data['work_phone_id'] = $phone->id;
        } else {
            $data['work_phone_id'] = Phone::create(['phone' => $data['work_phone']])->id;
        }

        unset($data['personal_phone']);
        unset($data['work_phone']);

        $data['sex'] = $data['sex']['id'];
        if ($data['light_work']) $data['light_work'] = $data['light_work']['id'];

        $employee = Employee::create($data);
        return $employee;
    }
}
