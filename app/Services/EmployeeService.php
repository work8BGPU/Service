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
        $data['position_id'] = $this->setFieldId($data['position'], Position::class);
        $data['shift_id'] = $this->setFieldId($data['shift'], Shift::class);
        $data['area_id'] = $this->setFieldId($data['area'], Area::class);

        $data['personal_phone_id'] = Phone::firstOrCreate(['phone' => $data['personal_phone']])->id;
        $data['work_phone_id'] = Phone::firstOrCreate(['phone' => $data['work_phone']])->id;

        $data['sex'] = $data['sex']['id'];
        if ($data['light_work']) $data['light_work'] = $data['light_work']['id'];
       
        $employee = Employee::create($data);
        return $employee;
    }
}
