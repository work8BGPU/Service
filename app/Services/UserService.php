<?php

namespace App\Services;

use App\Models\Phone;
use App\Models\User\Employee;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function getMe(): User
    {
        $user = Auth::user()->select('id', 'role_id', 'phone_id', 'employee_id')->with([
            'role' => function ($query) {
                $query->select('id', 'title');
            },
            'phone' => function ($query) {
                $query->select('id', 'phone');
            },
            'employee' => function ($query) {
                $query->select('id', 'name', 'lastname');
            }
        ])->first();

        return $user;
    }

    public function allWithRolePaginate(?int $perPage): LengthAwarePaginator
    {
        return User::query()->with('role')->paginate($perPage ?? 25)->withQueryString();
    }

    public function createData(): array
    {
        $roles = Role::all('id', 'title');
        $employees = Employee::all()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'fio' => $employee->fio,
            ];
        })->toArray();

        return [
            'roles' => $roles,
            'employees' => $employees
        ];
    }    
}
