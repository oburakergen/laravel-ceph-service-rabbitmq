<?php

namespace App\Repository\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class CrudClass
{
    public function __construct(protected Model $model){}

    /**
     * @throws \Exception
     */
    public function findOne(int $userId): Model
    {
        try {
            return $this->model->where(['user_id' => $userId])->active()->firstOrFail();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function create(array $data): Model
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function update($id, array $data): bool
    {
        try {
            return $this->model->find($id)->update($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}