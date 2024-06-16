<?php

namespace App\Services;

use App\Models\User\Employee;
use App\Models\User\Workday\JoblessDay;
use App\Models\User\Workday\JoblessStatus;
use App\Models\User\Workday\TimeWork;
use App\Models\User\Workday\Workday;

class WorkdayService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Workday::class);
    }

    public function createData(): array
    {
        $timeWorks = TimeWork::all('id', 'time');
        $statuses = JoblessStatus::all('id', 'title');

        $employees = Employee::all()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'fio_short' => $employee->fio_short,
            ];
        })->toArray();

        return [
            'statuses' => $statuses,
            'employees' => $employees,
            'time_works' => $timeWorks
        ];
    }

    public function create(array $data): Workday
    {
        $data['employee_id'] = $data['employee']['id'];
        $data['time_work_id'] = $this->setFieldId($data['time_work'], TimeWork::class, 'time');
        if ($data['new_time_work']) $data['new_time_work_id'] = $this->setFieldId($data['new_time_work'], TimeWork::class, 'time');
        if ($data['intern']) $data['intern'] = $data['intern']['id'];

        $workday = Workday::create($data);

        if (!$data['jobless_days']) return $workday;

        $joblessDays = $data['jobless_days'];
        $newJoblessDays = [];

        foreach ($joblessDays as $joblessDay) {
            $joblessDay['status_id'] = $this->setFieldId($joblessDay['status'], JoblessStatus::class);
            $newJoblessDay = new JoblessDay(['status_id' => $joblessDay['status_id'], 'start' => $joblessDay['start'], 'end' => $joblessDay['end']]);
            $newJoblessDays[] = $newJoblessDay;
        }

        $workday->joblessDays()->saveMany($newJoblessDays);

        return $workday;
    }
}
