<?php

namespace App\Services\Api;

use App\Services\Api\Contracts\BaseServiceInterface;
use App\Services\Api\Contracts\CanSortAndFilter;
use App\Services\Api\Values\ApiParam;
use App\Services\Traits\EloquentFilter;

abstract class BaseService implements BaseServiceInterface, CanSortAndFilter
{
    use EloquentFilter;

    /**
     * @var Eloquent model
     */
    protected $model;

    public function getList(ApiParam $param)
    {
        $query = $this->applyFilters($this->model, $param->toArray());

        return [
            'status_code' => 200,
            'is_error' => false,
            'message' => '',
            'data' => $query->apiPaginate($param),
        ];
    }

    public function getDetail($id)
    {
        return [
            'status_code' => 200,
            'is_error' => false,
            'message' => '',
            'data' => $this->model->find($id),
        ];
    }

    public function getSortable()
    {
        $fields = $this->model->getFillable() ?: [];
        $fields[] = $this->model->getKeyName();

        return $fields;
    }

    public function getFilterable()
    {
        return $this->model->getFillable() ?: [];
    }
}
