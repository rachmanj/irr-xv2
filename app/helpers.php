<?php

use App\Http\Controllers\DocumentLogController;

if (!function_exists('saveLog')) {
    function saveLog($model, $recordId, $type, $userId, $points)
    {
        $controller = new DocumentLogController();
        return $controller->saveLog($model, $recordId, $type, $userId, $points);
    }
}
