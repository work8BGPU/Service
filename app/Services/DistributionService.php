<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Request\Request;
use App\Models\User\Employee;
use App\Models\User\EmployeeRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DistributionService
{
    /**
     * Получение всех заявок и сотрудников.
     *
     * @return array
     */
    public function getRequestsAndEmployees(): array
    {
        $requests = Request::all();

        // Получаем сотрудников, которые не отдыхают на дату заявки и у которых есть расписание
        $employees = Employee::whereDoesntHave('workday.joblessDays', function ($query) use ($requests) {
            $query->where(function ($query) use ($requests) {
                foreach ($requests as $request) {
                    $query->orWhere(function ($query) use ($request) {
                        $query->whereDate('start', '<=', $request->date_meet)
                            ->whereDate('end', '>=', $request->date_meet);
                    });
                }
            });
        })->whereHas('workday')->get();

        return [$requests, $employees];
    }

    /**
     * Распределение заявок среди сотрудников.
     *
     * @param Collection $requests
     * @param Collection $employees
     * @return array
     */
    public function distributeRequests(Collection $requests, Collection $employees): array
    {
        $schedule = [];

        // Подсчитать количество текущих заявок у каждого сотрудника
        $employeesRequestCount = $employees->mapWithKeys(function ($employee) {
            return [$employee->id => $employee->requests()->count()];
        })->toArray();

        foreach ($requests as $request) {
            $requestEmployeesCount = $request->employees()->count();
            $requestEmployeesNeed = $request->insp_f + $request->insp_m;

            if ($requestEmployeesCount >= $requestEmployeesNeed) continue;

            $time_start = Carbon::parse($request->date_meet)->setTimeFromTimeString($request->time_start);
            $time_end = Carbon::parse($request->date_meet)->setTimeFromTimeString($request->time_end);
            if ($time_end->lt($time_start)) {
                $time_end->addDay();
            }

            $optimalEmployees = [
                'male' => [],
                'female' => []
            ];

            // Сортируем сотрудников по количеству текущих заявок
            $sortedEmployees = $employees->sortBy(function ($employee) use ($employeesRequestCount) {
                return $employeesRequestCount[$employee->id];
            });

            foreach ($sortedEmployees as $employee) {
                $workday = $employee->workday()->first();
                if (!$workday) {
                    continue;
                }

                $timeWork = $workday->timeWork()->first();
                if (!$timeWork) {
                    continue;
                }

                $work_times = explode("-", $timeWork->time);
                $work_start = Carbon::parse($time_start->toDateString() . ' ' . trim($work_times[0]));
                $work_end = Carbon::parse($time_start->toDateString() . ' ' . trim($work_times[1]));

                if ($work_end->lt($work_start)) {
                    $work_end->addDay();
                }

                if ($time_start->between($work_start, $work_end) && $time_end->between($work_start, $work_end)) {
                    $hasConflict = $employee->requests()
                        ->where(function ($query) use ($time_start, $time_end) {
                            $query->where(function ($query) use ($time_start, $time_end) {
                                $query->whereRaw("CONCAT(requests.date_meet, ' ', requests.time_start) <= ?", [$time_start])
                                    ->whereRaw("CONCAT(requests.date_meet, ' ', requests.time_end) >= ?", [$time_start]);
                            })
                                ->orWhere(function ($query) use ($time_start, $time_end) {
                                    $query->whereRaw("CONCAT(requests.date_meet, ' ', requests.time_start) <= ?", [$time_end])
                                        ->whereRaw("CONCAT(requests.date_meet, ' ', requests.time_end) >= ?", [$time_end]);
                                })
                                ->orWhere(function ($query) use ($time_start, $time_end) {
                                    $query->whereRaw("CONCAT(requests.date_meet, ' ', requests.time_start) >= ?", [$time_start])
                                        ->whereRaw("CONCAT(requests.date_meet, ' ', requests.time_end) <= ?", [$time_end]);
                                });
                        })
                        ->exists();

                    if (!$hasConflict) {
                        if ($employee->sex == Employee::SEX_MALE && count($optimalEmployees['male']) < $request->insp_m) {
                            $optimalEmployees['male'][] = $employee;
                        } elseif ($employee->sex == Employee::SEX_FEMALE && count($optimalEmployees['female']) < $request->insp_f) {
                            $optimalEmployees['female'][] = $employee;
                        }

                        if (count($optimalEmployees['male']) >= $request->insp_m && count($optimalEmployees['female']) >= $request->insp_f) {
                            break;
                        }
                    }
                }
            }

            if (count($optimalEmployees['male']) >= $request->insp_m && count($optimalEmployees['female']) >= $request->insp_f) {
                foreach ($optimalEmployees['male'] as $maleEmployee) {
                    $schedule[] = [
                        'employee_id' => $maleEmployee->id,
                        'request_id' => $request->id,
                        'time_start' => $time_start->toDateTimeString(),
                        'time_end' => $time_end->toDateTimeString(),
                    ];
                    // Увеличиваем количество заявок у сотрудника
                    $employeesRequestCount[$maleEmployee->id]++;
                }
                foreach ($optimalEmployees['female'] as $femaleEmployee) {
                    $schedule[] = [
                        'employee_id' => $femaleEmployee->id,
                        'request_id' => $request->id,
                        'time_start' => $time_start->toDateTimeString(),
                        'time_end' => $time_end->toDateTimeString(),
                    ];
                    // Увеличиваем количество заявок у сотрудника
                    $employeesRequestCount[$femaleEmployee->id]++;
                }
            } else {
                $schedule[] = [
                    'employee_id' => null,
                    'request_id' => $request->id,
                    'time_start' => $time_start->toDateTimeString(),
                    'time_end' => $time_end->toDateTimeString(),
                    'note' => 'Нет доступных сотрудников для выполнения заявки'
                ];
            }
        }

        return $schedule;
    }

    /**
     * Заполнение таблицы employee_requests.
     *
     * @param array $schedule
     * @return void
     */
    public function fillEmployeeRequestsTable(array $schedule): void
    {
        foreach ($schedule as $entry) {
            if ($entry['employee_id'] !== null) {
                EmployeeRequest::create([
                    'employee_id' => $entry['employee_id'],
                    'request_id' => $entry['request_id'],
                    'time_start' => $entry['time_start'],
                    'time_end' => $entry['time_end']
                ]);
            }
        }
    }
}
