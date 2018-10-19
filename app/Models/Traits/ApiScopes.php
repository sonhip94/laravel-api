<?php
namespace App\Models\Traits;

use App\Services\Api\Pagination\ApiPaginator;
use App\Services\Api\Values\ApiParam;

trait ApiScopes
{
    /**
     * Pagination for api (list items)
     *
     * @param ApiParam $apiParam
     *
     * @return ApiPaginator
     */
    public function scopeApiPaginate($query, ApiParam $apiParam)
    {
        $originalPaginator = $query->paginate($apiParam->getPageSize())
            ->appends($apiParam->getRequestParams());

        return ApiPaginator::newFromPaginator($originalPaginator);
    }
}
