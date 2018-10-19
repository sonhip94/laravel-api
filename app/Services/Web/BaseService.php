<?php

namespace App\Services\Web;

use App\Services\Web\Contracts\BaseServiceInterface;

abstract class BaseService implements BaseServiceInterface
{
    /**
     * @var Eloquent model
     */
    protected $model;
}
