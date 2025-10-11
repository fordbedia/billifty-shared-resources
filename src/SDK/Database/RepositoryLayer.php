<?php

namespace BilliftySDK\SharedResources\SDK\Database;

use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\Model;

abstract class RepositoryLayer
{
    protected Model $model;
    
    public function __construct()
    {
        $this->resolver();
    }
    public function resolver()
    {
        $model = app()->make($this->makeModel());
        if (!($model instanceof Model)) {
            throw new \RuntimeException('RepositoryLayer does not implement Model interface');
        }
        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    abstract public function makeModel(): string;

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findBy(string $field, string $value): self
    {
        $model = $this->model->where($field, $value)->first();

        return $model;
    }

    public function paginate(
        $query = null,
        int $perPage = 15,
        array $columns = ['*'],
        string $pageName = 'page',
        int|null $page = null
    ) {
        $query = $query ?: $this->model->newQuery();
        return $query->paginate($perPage, $columns, $pageName, $page);
    }

    public function create(array $data): Model|array
    {
        return $this->model->create($data);
    }

    public function isExists(string $field, string $value): bool
    {
        return $this->model->where($field, $value)->exists();
    }
}