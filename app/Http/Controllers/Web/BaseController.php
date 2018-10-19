<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Gate;

class BaseController extends Controller
{
    protected $viewData;

    public function __construct()
    {
        $this->viewData = [];
    }
}
