<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    private Model $model;

    protected function __construct(string $modelName)
    {
        $this->model = app($modelName);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allWithPaginate(?int $perPage = 25): LengthAwarePaginator
    {
        return $this->model->paginate($perPage ?? 25)->withQueryString();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }
}
