<?php

namespace App\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait EloquentFilter
{
    /**
     * @param Builder|Model $query
     * @param array $params Associative array ['filters', 'orders', 'ids']
     *
     * @return Builder
     */
    protected function applyFilters($query, array $params = [])
    {
        if (!$params) {
            return $query;
        }

        $newQuery = clone $query;
        $queries = [];

        if ($ids = $params['ids']) {
            $queries[] = $newQuery->whereKey($ids);
        }

        // Filter
        $defaultFilters = [];
        foreach ($params['filters'] as $field => $value) {
            $filterMethod = 'filterBy' . studly_case($field);

            if (method_exists($this, $filterMethod)) {
                $queries[] = $this->$filterMethod($newQuery, $value);
            } else {
                $defaultFilters[$field] = $value;
            }
        }

        if ($queries || $defaultFilters) {
            $newQuery = $newQuery->where(function ($curentQuery) use ($defaultFilters, $queries, $newQuery) {
                foreach ($queries as $q) {
                    $curentQuery->addNestedWhereQuery($q->getQuery());
                }

                foreach ($defaultFilters as $field => $value) {
                    $curentQuery->where($this->tableField($newQuery, $field), '=', $value);
                }
            });
        }

        // Order
        foreach ($params['orders'] as $field => $orderDir) {
            $sortMethod = 'sortBy' . studly_case($field);

            if (method_exists($this, $sortMethod)) {
                $newQuery = $this->$sortMethod($newQuery, $value);
            } else {
                $newQuery = $newQuery->orderBy($this->tableField($newQuery, $field), $orderDir);
            }
        }

        return $newQuery;
    }

    /**
     * @param Builder|Model $query
     * @param string $fieldName Table field name
     *
     * @return string Full table field name
     */
    protected function tableField($query, $fieldName)
    {
        if ($query instanceof Builder) {
            return $query->getModel()->getTable() . '.' . $fieldName;
        }

        if ($query instanceof Model) {
            return $query->getTable() . '.' . $fieldName;
        }

        return $fieldName;
    }
}
