<?php

namespace App\Http\Controllers\Admin\Pipeline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CRMProspectLogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:create,App\Models\CRMProspectLogs')->only('create');
    }
}
