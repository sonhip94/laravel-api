<?php
namespace App\Services\Api\Contracts;

use App\Services\Api\Values\ApiParam;

interface BaseServiceInterface
{
    /**
     * Get list items
     *
     * @return array [
     *       'status_code' => int, http status code or user-defined code,
     *       'is_error' => boolean,
     *       'message' => string,
     *       'data' => array or \App\Services\Pagination\ApiPaginator,
     *   ]
     */
    public function getList(ApiParam $param);

    /**
     * Get item detail
     *
     * @return array [
     *       'status_code' => int, http status code or user-defined code,
     *       'is_error' => boolean,
     *       'message' => string,
     *       'data' => array or Eloquent model,
     *   ]
     */
    public function getDetail($id);
}
