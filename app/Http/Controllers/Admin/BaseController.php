<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;

class BaseController extends Controller
{
    protected $viewData;

    public function __construct()
    {
        $this->viewData = [];
    }

    public function checkPermission($permission, $msg = null)
    {
        return abort_if(Gate::denies('permission', $permission), 403, $msg);
    }
}
