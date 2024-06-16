<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Request\Request;
use App\Models\User\Employee;
use App\Models\User\EmployeeRequest;
use Illuminate\Support\Collection;

class DistributionService
{
    /**
     * Получение заявок и сотрудников на следующие сутки.
     *
     * @return array
     */
    public function getTomorrowRequestsAndEmployees(): array
    {
        // Получаем дату следующего дня
        $tomorrow = Carbon::tomorrow();

        // Получаем заявки на следующие сутки
        $requests = Request::whereDate('date_meet', $tomorrow)->get();

        // Получаем сотрудников, которые не отдыхают на следующие сутки
        $employees = Employee::whereDoesntHave('workday.joblessDays', function ($query) use ($tomorrow) {
            $query->whereDate('start', '<=', $tomorrow)
                ->whereDate('end', '>=', $tomorrow);
        })->get();

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
        $employeeCount = $employees->count();

        if ($employeeCount == 0) {
            // Если нет доступных сотрудников
            return array_map(function ($request) {
                return [
                    'employee_id' => null,
                    'request_id' => $request->id,
                    'time_start' => $request->date_meet . ' ' . $request->time_start,
                    'time_end' => $request->date_meet . ' ' . $request->time_end,
                    'note' => 'No available employees'
                ];
            }, $requests->all());
        }

        foreach ($requests as $index => $request) {
            $time_start = Carbon::parse($request->date_meet)->setTimeFromTimeString($request->time_start);
            $time_end = Carbon::parse($request->date_meet)->setTimeFromTimeString($request->time_end);

            // Получаем индекс сотрудника для равномерного распределения
            $employeeIndex = $index % $employeeCount;
            $employee = $employees[$employeeIndex];

            // Проверяем, работает ли сотрудник в это время
            if ($employee->workday) {
                $workday = $employee->workday;
                $work_start = Carbon::parse($time_start->toDateString() . ' ' . $workday->time_start);
                $work_end = Carbon::parse($time_start->toDateString() . ' ' . $workday->time_end);

                if ($time_start->between($work_start, $work_end) && $time_end->between($work_start, $work_end)) {
                    $schedule[] = [
                        'employee_id' => $employee->id,
                        'request_id' => $request->id,
                        'time_start' => $time_start->toDateTimeString(),
                        'time_end' => $time_end->toDateTimeString(),
                    ];
                } else {
                    $schedule[] = [
                        'employee_id' => null,
                        'request_id' => $request->id,
                        'time_start' => $time_start->toDateTimeString(),
                        'time_end' => $time_end->toDateTimeString(),
                        'note' => 'Employee not available during this time'
                    ];
                }
            } else {
                $schedule[] = [
                    'employee_id' => null,
                    'request_id' => $request->id,
                    'time_start' => $time_start->toDateTimeString(),
                    'time_end' => $time_end->toDateTimeString(),
                    'note' => 'No workday information'
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

    /**
     * Визуализация расписания.
     *
     * @param array $schedule
     * @return void
     */
    public function visualizeSchedule(array $schedule): void
    {
        foreach ($schedule as $entry) {
            echo "Employee ID: " . ($entry['employee_id'] ?? 'None') . "\n";
            echo "Request ID: " . $entry['request_id'] . "\n";
            echo "Time Start: " . $entry['time_start'] . "\n";
            echo "Time End: " . $entry['time_end'] . "\n";
            if (isset($entry['note'])) {
                echo "Note: " . $entry['note'] . "\n";
            }
            echo "------------------\n";
        }
    }
}
