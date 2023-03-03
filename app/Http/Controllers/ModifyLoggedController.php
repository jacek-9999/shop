<?php

namespace App\Http\Controllers;

use App\Services\ModifyLogService;

abstract class ModifyLoggedController extends Controller
{
    protected ModifyLogService $modifyLog;
    public function __construct(ModifyLogService $modifyLog)
    {
        $this->modifyLog = $modifyLog;
    }
}
