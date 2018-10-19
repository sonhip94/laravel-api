<?php
namespace App\Services\Api\Pagination;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Api\Values\ApiParam;

class ApiPaginator extends LengthAwarePaginator
{
    /**
     * @param LengthAwarePaginator $paginator
     *
     * @return ApiPaginator
     */
    public static function newFromPaginator(LengthAwarePaginatorContract $paginator)
    {
        return new ApiPaginator(
            $paginator->items,
            $paginator->total,
            $paginator->perPage,
            $paginator->currentPage,
            [
                'query' => $paginator->query,
                'path' => $paginator->path,
                'fragment' => $paginator->fragment,
                'pageName' => $paginator->pageName,
            ]
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'results' => $this->items->toArray(),
            'count' => $this->total(),
            'next' => $this->nextPageUrl(),
            'previous' => $this->previousPageUrl(),
        ];
    }
}
