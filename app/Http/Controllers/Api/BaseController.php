<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\Pagination\ApiPaginator;

/**
 * @apiDefine RequireAuthHeader
 *
 * @apiHeader {String} Authorization Authorization Bearer token after login
 * @apiHeaderExample {json} Header-Example:
 * {
 *     "Authorization": "Bearer jwt-token-after-login"
 * }
 */

class BaseController extends Controller
{
    public function __construct()
    {
    }

    public function responseErrors($returnCode, $message, $statusCode = 200)
    {
        return api_errors($returnCode, $message, $statusCode);
    }

    public function responseSuccess($data, $statusCode = 200)
    {
        return api_success($data, $statusCode);
    }

    public function responseFromService($serviceResults, $dataName = 'data')
    {
        $data = $serviceResults['data'];
        $statusCode = $serviceResults['status_code'];

        if ($data instanceof ApiPaginator) {
            $data = $data->toArray();
        }

        if ($serviceResults['is_error']) {
            return $this->responseErrors(
                $statusCode,
                $serviceResults['message'],
                $statusCode
            );
        }

        if (!isset($data['results'])) {
            $data = ['results' => $data];
        }

        return $this->responseSuccess([
            $dataName => $data,
            'code' => $statusCode,
        ]);
    }
}
